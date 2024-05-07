<?php

namespace Dm1tru\Barcoder\Application;

use Dm1tru\Barcoder\Domain\ValueObject\Token;

interface RequestInterface
{
    public function getPath(): array;

    public function getAuthToken(): Token;
}
