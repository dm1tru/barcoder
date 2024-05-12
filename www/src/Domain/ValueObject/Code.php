<?php

namespace Dm1tru\Barcoder\Domain\ValueObject;

class Code
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $code);
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
