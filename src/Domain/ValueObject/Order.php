<?php

namespace Dm1tru\Barcoder\Domain\ValueObject;

class Order
{
    private int $order;

    public function __construct(int $order)
    {
        $this->order = $order;
    }

    public function getOrder(): string
    {
        return $this->order;
    }
}
