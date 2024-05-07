<?php

namespace Dm1tru\Barcoder\Application;

use Dm1tru\Barcoder\Domain\Entity\Response;
use Dm1tru\Barcoder\Domain\Entity\User;
use Dm1tru\Barcoder\Domain\Repository\BarcodeRepositoryInterface;
use Dm1tru\Barcoder\Domain\Repository\DeviceRepositoryInterface;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;

class Api
{
    private array $path;
    private User $user;
    private DeviceRepositoryInterface $deviceRepository;
    private BarcodeRepositoryInterface $barcodeRepository;

    public function __construct(
        array $path,
        User $user,
        DeviceRepositoryInterface $deviceRepository,
        BarcodeRepositoryInterface $barcodeRepository
    ) {
        $this->path = $path;
        $this->user = $user;
        $this->deviceRepository = $deviceRepository;
        $this->barcodeRepository = $barcodeRepository;
    }

    public function getResponse(): ?Response
    {
        switch ($this->path[0]) {
            case 'devices':
                return $this->getDevices();
                break;
            case 'codes':
                return $this->getCodes();
                break;
            default:
                throw new \Exception("Неверная команда", 404);
                break;
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
                throw  new \Exception("Неверная команда", 404);
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
        $ts = 0;
        if (count($this->path) == 1) {
            $ts = 0;
        }
        if (count($this->path) == 2) {
            if (preg_match('/^([0-9]+)$/i', $this->path[1], $m)) {
                $ts = (int)$m[1];
            } else {
                throw  new \Exception("Неверная команда", 404);
            }
        }

        if (count($this->path) > 2) {
            throw new \Exception("Неверная команда", 404);
        }

        $codes = $this->barcodeRepository->getAfterDate(new Date($ts));
        $ret = [];
        foreach ($codes as $code) {
            $ret[] = $code->asArray();
        }
        $resp = new Response($ret);
        return $resp;
    }
}
