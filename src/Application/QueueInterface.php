<?php

namespace Dm1tru\Barcoder\Application;

use Dm1tru\Barcoder\Domain\Entity\Barcode;

interface QueueInterface
{
    public function send(Barcode $code): void;

    public function receive(): ?Barcode;
}
