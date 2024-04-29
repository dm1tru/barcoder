<?php

namespace Dm1tru\Barcoder\Application;

use Dm1tru\Barcoder\Domain\Entity\Barcode;
use Dm1tru\Barcoder\Domain\Repository\BarcodeRepositoryInterface;
use Dm1tru\Barcoder\Domain\Repository\DeviceRepositoryInterface;
use Workerman\Worker;

class BarcodeServer
{
    private Worker $worker;
    private \Psr\Log\LoggerInterface $logger;
    private DeviceRepositoryInterface $deviceRepository;
    private BarcodeRepositoryInterface $barcodeRepository;
    private array $device_ids = [];

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        DeviceRepositoryInterface $deviceRepository,
        BarcodeRepositoryInterface $barcodeRepository
    ) {
        $this->deviceRepository = $deviceRepository;
        $this->barcodeRepository = $barcodeRepository;

        $this->logger = $logger;

        $conf = parse_ini_file('config.ini', true);
        $this->worker = new Worker("tcp://$conf[TCP_SERVER_HOST]:$conf[TCP_SERVER_PORT]");
        $this->worker->count = 2;
        $this->worker->onConnect = [$this, 'connect'];
        $this->worker->onMessage = [$this, 'message'];
        $this->worker->onClose = [$this, 'close'];
        $this->logger->info('BarcodeServer created');
    }

    public function close($connection)
    {
        $this->logger->info('BarcodeServer connection close', ['id' => $connection->id]);
    }

    public function connect($connection)
    {
        $remoteIp = $connection->getRemoteIp();
        $device = false;
        $devices = $this->deviceRepository->getAll();
        foreach ($devices as $r) {
            if ($r->getHost()->getIp() == $remoteIp || $r->getHost()->getIp() == '%') {
                $device = $r;
                break;
            }
        }

        if (!$device) {
            $connection->close('Wrong IP. Bye Bye');
            $this->logger->error('BarcodeServer error connect from ', ['ip' => $remoteIp]);
            return;
        }

        $this->device_ids[$connection->id] = $device->getId()->getId();
        $this->logger->info('BarcodeServer connect', [
            'id' => $connection->id,
            'ip' => $remoteIp,
            'name' => $device->getName()->getName()
        ]);
    }

    public function message($connection, $data)
    {
        $data = trim($data);

        if ($data == 'exit') {
            $connection->close();
            return;
        }


        if (preg_match('/^(.+)\,([0-9]+)$/i', $data, $m)) {
            $code = $m[1];
            $count = (int)$m[2];
        } else {
            $code = $data;
            $count = 1;
        }

        $dev_id = $this->device_ids[$connection->id];

        $barcode = new Barcode(
            new \Dm1tru\Barcoder\Domain\ValueObject\Id(0),
            new \Dm1tru\Barcoder\Domain\ValueObject\Id($dev_id),
            new \Dm1tru\Barcoder\Domain\ValueObject\Code($code),
            new \Dm1tru\Barcoder\Domain\ValueObject\Count($count),
            new \Dm1tru\Barcoder\Domain\ValueObject\Date()
        );

        $id = $this->barcodeRepository->add($barcode);
        $this->logger->info('BarcodeServer add barcode', [
            'id' => $connection->id,
            'code' => $code,
            'count' => $count,
            'barcode_id' => $id->getId(),
            'device_id' => $dev_id
        ]);
    }

    public function run()
    {
        Worker::runAll();
        $this->logger->info('BarcodeServer run');
    }
}
