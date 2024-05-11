<?php

namespace Dm1tru\Barcoder\Application;


use Workerman\Timer;
use Workerman\Worker;

class WebsocketServer
{
    private Worker $worker;
    private \Psr\Log\LoggerInterface $logger;
    private QueueInterface $queue;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        QueueInterface           $queue
    )
    {
        $this->queue = $queue;
        $this->logger = $logger;

        $conf = parse_ini_file('config.ini', true);
        $this->worker = new Worker("websocket://0.0.0.0:$conf[WEBSOCKET_PORT]");
        $this->worker->count = 1;
        $this->worker->onConnect = [$this, 'connect'];
        $this->worker->onMessage = [$this, 'message'];
        $this->worker->onClose = [$this, 'close'];
        $this->worker->onWorkerStart = [$this, 'start'];

        $this->logger->info('Websocket created');
    }

    public function start()
    {
        Timer::add(0.5, [$this, 'listenQueue']);
    }

    public function close($connection)
    {
        $this->logger->info('Websocket connection close', ['id' => $connection->id]);
    }

    public function connect($connection)
    {

        $this->logger->info('BarcodeServer connect', [
            'id' => $connection->id,
            'ip' => $connection->getRemoteIp()
        ]);

        $connection->send('Hello');
    }

    public function message($connection, $data)
    {
        $data = trim($data);

        if ($data == 'exit') {
            $connection->close();
            return;
        }
    }

    public function listenQueue()
    {
        $this->queue->consume([$this, 'getQueueMessage']);
    }

    public function getQueueMessage($msg)
    {

        $code = unserialize($msg->body);
        foreach ($this->worker->connections as $c) {
            $c->send(json_encode($code->asArray()));
        }
    }

    public function run()
    {
        Worker::runAll();
        $this->logger->info('BarcodeServer run');
    }
}
