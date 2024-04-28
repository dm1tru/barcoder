<?php

namespace Dm1tru\Barcoder\Domain\Repository;

use Dm1tru\Barcoder\Domain\Entity\Barcode;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;

interface BarcodeRepositoryInterface
{
    public function add(Barcode $barcode): Id;

    public function getAll(int $limit = 100, int $offset = 0): array;

    public function getAfterDate(Date $date, int $limit = 100, int $offset = 0): array;
}
