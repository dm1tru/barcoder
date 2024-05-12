<?php

namespace Dm1tru\Barcoder\Application;

use Dm1tru\Barcoder\Domain\Entity\Barcode;
use Dm1tru\Barcoder\Domain\Entity\Response;
use Dm1tru\Barcoder\Domain\Entity\User;
use Dm1tru\Barcoder\Domain\Repository\BarcodeRepositoryInterface;
use Dm1tru\Barcoder\Domain\Repository\DeviceRepositoryInterface;
use Dm1tru\Barcoder\Domain\ValueObject\Code;
use Dm1tru\Barcoder\Domain\ValueObject\Count;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;
use Dm1tru\Barcoder\Domain\ValueObject\Method;

class Api
{
    private array $path;
    private RequestInterface $request;
    private User $user;
    private DeviceRepositoryInterface $deviceRepository;
    private BarcodeRepositoryInterface $barcodeRepository;
    private QueueInterface $queue;

    public function __construct(
        RequestInterface           $request,
        User                       $user,
        DeviceRepositoryInterface  $deviceRepository,
        BarcodeRepositoryInterface $barcodeRepository,
        QueueInterface             $queue
    )
    {
        $this->request = $request;
        $this->user = $user;
        $this->deviceRepository = $deviceRepository;
        $this->barcodeRepository = $barcodeRepository;
        $this->queue = $queue;
    }

    public function makeResponse()
    {
        try {
            $this->path = $this->request->getPath();
            switch ($this->path[0]) {
                case 'devices':
                    $response = $this->getDevices();
                    break;
                case 'codes':
                    $response = $this->getCodes();
                    break;
                default:
                    throw new \Exception("Неверная команда", 404);
                    $response;
            }
        } catch (\Exception $e) {
            $response = new Response(['error' => $e->getMessage()], $e->getCode());
        } finally {
            $response->send();
        }
    }

    public function getDevices(): Response
    {
        if (count($this->path) > 2) {
            throw new \Exception('Неверная команда', 404);
        }

        if (count($this->path) == 2) {
            if (preg_match('/^([0-9]+)$/i', $this->path[1], $m)) {
                return $this->getDeviceById((int)$m[1]);
            } else {
                throw  new \Exception("Неверная команда", 400);
            }
        }

        return $this->getDevicesList();
    }

    private function getDeviceById(int $id): Response
    {
        $dev = $this->deviceRepository->getById(new id($id));
        if (!$dev) {
            throw  new \Exception("Устройство не найдено", 404);
        }

        return new Response($dev->asArray());
    }

    private function getDevicesList(): Response
    {
        $devs = $this->deviceRepository->getAll();
        $ret = [];
        foreach ($devs as $d) {
            $ret[] = $d->asArray();
        }

        $resp = new Response($ret);
        return $resp;
    }

    private function getCodes()
    {

        switch ($this->request->getMethod()) {
            case Method::GET:
                return $this->getCodesGet();
                break;
            case Method::POST:
                return $this->getCodesPost();
                break;
            default:
                throw new \Exception("Неверный метод", 400);
                break;
        }
    }

    private function getCodesGet()
    {
        if (count($this->path) != 1) {
            throw new \Exception("Неверная команда", 404);
        }

        $params = $this->request->getParameters();
        $codes = $this->barcodeRepository->getAll(
            isset($params['start_id']) ? intval($params['start_id']) : null,
            isset($params['end_id']) ? intval($params['end_id']) : null,
            isset($params['start_date']) ? intval($params['start_date']) : null,
            isset($params['end_date']) ? intval($params['end_date']) : null,
            isset($params['device_id']) ? intval($params['device_id']) : null,
            isset($params['limit']) ? intval($params['limit']) : 100,
            isset($params['offset']) ? intval($params['offset']) : 0
        );

        $ret = [];
        foreach ($codes as $code) {
            $ret[] = $code->asArray();
        }
        return new Response($ret);
    }

    private function getCodesPost()
    {
        $params = $this->request->getParameters();
        if (!isset($params['code'])) {
            throw new \Exception("Не указан код", 400);
        }

        if (!isset($params['device_id'])) {
            throw new \Exception("Не указан параметр device_id", 400);
        }
        if (!preg_match('/^([0-9]+)$/i', $params['device_id'], $m)) {
            throw new \Exception("Неверное значение device_id", 400);
        }

        $device = $this->deviceRepository->getById(new id($params['device_id']));
        if (!$device) {
            throw new \Exception("Устройство не найдено", 404);
        }

        $date = isset($params['date']) ? intval($params['date']) : time();
        $count = isset($params['count']) ? intval($params['count']) : 1;

        $barcode = new Barcode(
            new Id(0),
            new Id($params['device_id']),
            new Code($params['code']),
            new Count($count),
            new Date($date)
        );

        $barcode = $this->barcodeRepository->add($barcode);
        $this->queue->send($barcode);
        return new Response($barcode->asArray());
    }
}
