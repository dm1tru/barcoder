<?php

namespace Dm1tru\Barcoder\Domain\Repository;

use Dm1tru\Barcoder\Domain\Entity\Barcode;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;

interface BarcodeRepositoryInterface
{
    public function add(Barcode $barcode): Barcode;

    public function getAll(
        int $start_id = null,
        int $end_id = null,
        int $start_date = null,
        int $end_date = null,
        int $device_id = null,
        int $limit = 100,
        int $offset = 0
    ): array;

    public function getAfterDate(Date $date, int $limit = 100, int $offset = 0): array;
}
