<?php

namespace Dm1tru\Barcoder\Application;

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

        /*
                $tcp_worker->onMessage = function ($connection, $data) {
                    //$request->get();
                    //$request->post();
                    //$request->header();
                    //$request->cookie();
                    //$request->session();
                    //$request->uri();
                    //$request->path();
                    //$request->method();

                    // Send data to client
                    //$connection->send("Hello $data \n");
                };
        */

// Emitted when connection is closed
    }

    public function close($connection)
    {
        $this->logger->info('BarcodeServer connection close', ['id' => $connection->id]);
        //echo "Connection closed\n";
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
        echo "Message: $data\n";

        var_dump($data);

        if ($data == 'exit') {
            $connection->close('close message');
        }
        //var_dump($connection);

        //$connection->send("Hello $data \n");
    }

    public function run()
    {
        Worker::runAll();
        $this->logger->info('BarcodeServer run');
    }
}
