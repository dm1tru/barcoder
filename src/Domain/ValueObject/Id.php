<?php

namespace Dm1tru\Barcoder\Domain\ValueObject;

class Id
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
