<?php

namespace Dm1tru\Barcoder\Domain\Entity;

class Response
{
    private mixed $data;

    public function __construct(mixed $data)
    {
        $this->data = $data;
    }

    public function getJson()
    {
        return json_encode(['response' => $this->data]);
    }
}

