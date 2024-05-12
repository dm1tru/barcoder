<?php

namespace Dm1tru\Barcoder\Domain\Entity;

use Dm1tru\Barcoder\Domain\ValueObject\Code;
use Dm1tru\Barcoder\Domain\ValueObject\Count;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;
use Dm1tru\Barcoder\Domain\ValueObject\Name;
use Dm1tru\Barcoder\Domain\ValueObject\Token;

class User
{
    private Id $id;
    private Name $name;
    private Token $token;

    public function __construct(Id $id, Name $name, Token $token)
    {
        $this->id = $id;
        $this->name = $name;
        $this->token = $token;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getToken(): Token
    {
        return $this->token;
    }
}
