<?php

namespace Dm1tru\Barcoder\Domain\ValueObject;

class Count
{
    private int $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public function getCount(): string
    {
        return $this->count;
    }
}
