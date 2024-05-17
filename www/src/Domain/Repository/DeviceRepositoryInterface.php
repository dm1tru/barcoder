<?php

namespace Dm1tru\Barcoder\Domain\Repository;

use Dm1tru\Barcoder\Domain\Entity\Barcode;
use Dm1tru\Barcoder\Domain\Entity\Device;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;
use Dm1tru\Barcoder\Domain\ValueObject\Ip;
use Dm1tru\Barcoder\Domain\ValueObject\Name;
use Dm1tru\Barcoder\Domain\ValueObject\Order;

interface DeviceRepositoryInterface
{
    public function add(Device $device): Id;

    public function getAll(): array;

    public function getById(Id $id): ?Device;

    public function update(id $id, ?Name $name, ?Ip $ip, ?Order $order): Device;

    public function deleteById(Id $id): void;
}
