<?php

namespace Dm1tru\Barcoder\Domain\Entity;

use Dm1tru\Barcoder\Domain\ValueObject\Id;
use Dm1tru\Barcoder\Domain\ValueObject\Ip;
use Dm1tru\Barcoder\Domain\ValueObject\Name;
use Dm1tru\Barcoder\Domain\ValueObject\Order;

class Device
{
    private Id $id;
    private Name $name;
    private Ip $host;
    private Order $order;

    public function __construct(Id $id, Name $name, Ip $host, Order $order)
    {
        $this->id = $id;
        $this->name = $name;
        $this->host = $host;
        $this->order = $order;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getHost(): ip
    {
        return $this->host;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id->getId(),
            'name' => $this->name->getName(),
            'host' => $this->host->getIp(),
            'order' => $this->order->getOrder()
        ];
    }
}
