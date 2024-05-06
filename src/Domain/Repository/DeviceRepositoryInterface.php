<?php

namespace Dm1tru\Barcoder\Domain\Repository;

use Dm1tru\Barcoder\Domain\Entity\Barcode;
use Dm1tru\Barcoder\Domain\Entity\Device;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;

interface DeviceRepositoryInterface
{
    public function add(Device $device): Id;

    public function getAll(): array;

    public function getById(Id $id): ?Device;
}
