<?php

namespace Dm1tru\Barcoder\Domain\ValueObject;

class Date
{
    private int $timestamp;

    public function __construct(?int $timestamp = null)
    {
        $this->timestamp = $timestamp ?? time();
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getDate(string $format): string
    {
        return date($format, $this->timestamp);
    }
}
