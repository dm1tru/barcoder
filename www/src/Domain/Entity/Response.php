<?php

namespace Dm1tru\Barcoder\Domain\Entity;

class Response
{
    private mixed $data;
    private int $code;

    public function __construct(mixed $data, $code = 200)
    {
        $this->data = $data;
        $this->code = $code;
    }

    public function getJson(): string
    {
        return json_encode(['response' => $this->data], JSON_UNESCAPED_UNICODE);
    }

    public function send(): void
    {
        header('Content-Type: application/json');
        http_response_code($this->code);
        echo $this->getJson();
    }
}
