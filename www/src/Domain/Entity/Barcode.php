<?php

namespace Dm1tru\Barcoder\Domain\Entity;

use Dm1tru\Barcoder\Domain\ValueObject\Code;
use Dm1tru\Barcoder\Domain\ValueObject\Count;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;

class Barcode
{
    private Id $id;
    private Code $code;
    private Count $count;
    private Date $date;
    private Id $deviceId;

    public function __construct(Id $id, Id $deviceId, Code $code, Count $count, Date $date)
    {
        $this->id = $id;
        $this->code = $code;
        $this->count = $count;
        $this->date = $date;
        $this->deviceId = $deviceId;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getCode(): Code
    {
        return $this->code;
    }

    public function getCount(): Count
    {
        return $this->count;
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    public function getDeviceId()
    {
        return $this->deviceId;
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id->getId(),
            'code' => $this->code->getCode(),
            'count' => $this->count->getCount(),
            'date' => $this->date->getTimestamp(),
            'device_id' => $this->deviceId->getId()
        ];
    }
}
