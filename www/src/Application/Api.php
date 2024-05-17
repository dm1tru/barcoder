<?php

namespace Dm1tru\Barcoder\Application;

use Dm1tru\Barcoder\Domain\Entity\Barcode;
use Dm1tru\Barcoder\Domain\Entity\Device;
use Dm1tru\Barcoder\Domain\Entity\Response;
use Dm1tru\Barcoder\Domain\Entity\User;
use Dm1tru\Barcoder\Domain\Repository\BarcodeRepositoryInterface;
use Dm1tru\Barcoder\Domain\Repository\DeviceRepositoryInterface;
use Dm1tru\Barcoder\Domain\ValueObject\Code;
use Dm1tru\Barcoder\Domain\ValueObject\Count;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;
use Dm1tru\Barcoder\Domain\ValueObject\Ip;
use Dm1tru\Barcoder\Domain\ValueObject\Method;
use Dm1tru\Barcoder\Domain\ValueObject\Name;
use Dm1tru\Barcoder\Domain\ValueObject\Order;

class Api
{
    private array $path;
    private RequestInterface $request;
    private User $user;
    private DeviceRepositoryInterface $deviceRepository;
    private BarcodeRepositoryInterface $barcodeRepository;
    private QueueInterface $queue;

    public function __construct(
        RequestInterface $request,
        User $user,
        DeviceRepositoryInterface $deviceRepository,
        BarcodeRepositoryInterface $barcodeRepository,
        QueueInterface $queue
    ) {
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
                case 'device':
                    $response = $this->getDevice();
                    break;
                case 'codes':
                    $response = $this->getCodes();
                    break;
                default:
                    throw new \Exception("Неверная команда", 404);
                    break;
            }
        } catch (\Exception $e) {
            $response = new Response(['error' => $e->getMessage()], $e->getCode());
        } finally {
            $response->send();
        }
    }

    public function getDevices(): Response
    {
        if (count($this->path) > 1) {
            throw new \Exception('Неверная команда', 404);
        }

        switch ($this->request->getMethod()) {
            case Method::GET:
                return $this->getDevicesList();
                break;
            case Method::POST:
                return $this->getDevicesPost();
                break;
            default:
                throw new \Exception("Неверный метод", 400);
                break;
        }
    }

    private function getDevice(): Response
    {

        if (count($this->path) != 2) {
            throw new \Exception('Неверная команда', 404);
        }

        if (preg_match('/^([0-9]+)$/i', $this->path[1], $m)) {
            $dev_id = (int)$m[1];
        } else {
            throw  new \Exception("Неверная команда", 400);
        }

        $dev = $this->deviceRepository->getById(new id($dev_id));
        if (!$dev) {
            throw  new \Exception("Устройство не найдено", 404);
        }

        switch ($this->request->getMethod()) {
            case Method::GET:
                return $this->getDeviceGet($dev_id);
                break;
            case Method::PUT:
                return $this->getDevicePut($dev_id);
                break;
            case Method::DELETE:
                return $this->getDeviceDelete($dev_id);
                break;
            default:
                throw new \Exception("Неверный метод", 400);
                break;
        }
    }

    private function getDevicePut(int $id): Response
    {
        $params = $this->request->getParameters();
        $is_empty = true;
        if (isset($params['host'])) {
            $ip = new Ip($params['host']);
            $is_empty = false;
        } else {
            $ip = null;
        }

        if (isset($params['name'])) {
            $name = new Name($params['name']);
            $is_empty = false;
        } else {
            $name = null;
        }

        if (isset($params['order']) && intval($params['order'])) {
            $order = new Order(intval($params['order']));
            $is_empty = false;
        } else {
            $order = null;
        }

        if ($is_empty) {
            throw new \Exception("Отсутствуют параметры", 400);
        }

        $dev = $this->deviceRepository->update(new Id($id), $name, $ip, $order);
        return new Response($dev->asArray());
    }

    private function getDeviceDelete($id)
    {
        $this->deviceRepository->deleteById(new Id($id));
        return new Response('OK');
    }

    private function getDeviceGet(int $id): Response
    {
        $dev = $this->deviceRepository->getById(new id($id));
        return new Response($dev->asArray());
    }

    private function getDevicesPost(): Response
    {
        $params = $this->request->getParameters();
        if (!isset($params['name'])) {
            throw new \Exception("Не указано название", 400);
        }

        if (!isset($params['host'])) {
            throw new \Exception("Не указан хост", 400);
        }

        if (!isset($params['order']) || !intval($params['order'])) {
            $params['order'] = 1000;
        }

        $device = new Device(
            new Id(0),
            new Name($params['name']),
            new Ip($params['host']),
            new Order(intval($params['order']))
        );

        $id = $this->deviceRepository->add($device);
        return new Response(['id' => $id->getId()]);
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
