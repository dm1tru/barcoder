<?php

namespace Dm1tru\Barcoder\Domain\ValueObject;

class Code
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = trim($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
