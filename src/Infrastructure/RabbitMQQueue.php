<?php

namespace Dm1tru\Barcoder\Infrastructure;

use Dm1tru\Barcoder\Application\QueueInterface;
use Dm1tru\Barcoder\Domain\Entity\Barcode;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQQueue implements QueueInterface
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    private string $queue_name;

    public function __construct()
    {
        $conf = parse_ini_file('config.ini', true);
        $this->makeConnection($conf['RABBITMQ_HOST'], $conf['RABBITMQ_PORT'], $conf['RABBITMQ_USER'], $conf['RABBITMQ_PASSWORD']);
        $this->makeChannel($conf['RABBITMQ_QUEUE']);
        $this->queue_name = $conf['RABBITMQ_QUEUE'];
    }

    private function makeConnection($host, $port, $user, $password)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
    }

    private function makeChannel($queue_name)
    {
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($queue_name, false, false, false, false);
    }

    public function send(Barcode $code): void
    {
        $msg = new AMQPMessage(serialize($code));
        $this->channel->basic_publish($msg, '', $this->queue_name);
    }

    public function consume(callable $callback): void
    {
        $this->channel->wait(null, true);
        $this->channel->basic_consume($this->queue_name, '', false, true, false, false, $callback);
    }
}
