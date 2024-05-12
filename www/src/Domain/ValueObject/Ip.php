<?php

namespace Dm1tru\Barcoder\Domain\ValueObject;

class Ip
{
    private string $ip;

    public function __construct(string $ip)
    {
        $this->ip = $ip;
    }

    public function getIp(): string
    {
        return $this->ip;
    }
}
