<?php

namespace Dm1tru\Barcoder\Domain\Repository;

use Dm1tru\Barcoder\Domain\Entity\User;
use Dm1tru\Barcoder\Domain\ValueObject\Id;
use Dm1tru\Barcoder\Domain\ValueObject\Token;

interface UserRepositoryInterface
{
    public function add(User $user): Id;

    public function getAll(): array;

    public function getById(Id $id): ?User;

    public function getByToken(Token $token): ?User;
}
