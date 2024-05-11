<?php

declare(strict_types=1);

namespace Dm1tru\Barcoder;

use Dm1tru\Barcoder\Application\Api;
use Dm1tru\Barcoder\Application\BarcodeServer;
use Dm1tru\Barcoder\Domain\Entity\User;
use Dm1tru\Barcoder\Infrastructure\HttpRequest;
use Dm1tru\Barcoder\Infrastructure\MysqlBarcodeRepository;
use Dm1tru\Barcoder\Infrastructure\MysqlDeviceRepository;
use Dm1tru\Barcoder\Infrastructure\MysqlUserRepository;
use Dm1tru\Barcoder\Infrastructure\RabbitMQQueue;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class App
{
    public function runBarcodeServer()
    {
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler(__DIR__ . '/../log/log.log', Level::Debug));

        $deviceRepository = new MysqlDeviceRepository();
        $barcodeRepository = new MysqlBarcodeRepository();

        $queue = new RabbitMQQueue();

        $server = new BarcodeServer($log, $deviceRepository, $barcodeRepository, $queue);
        $server->run();
    }

    public function getApiRequest()
    {
        $request = new HttpRequest();
        $userRepository = new MysqlUserRepository();
        $user = $userRepository->getByToken($request->getAuthToken());

        $deviceRepository = new MysqlDeviceRepository();
        $barcodeRepository = new MysqlBarcodeRepository();

        if (!$user) {
            throw new \Exception('Not found user', 403);
        }

        $api = new Api($request->getPath(), $user, $deviceRepository, $barcodeRepository);
        $response = $api->getResponse();

        var_dump($response->getJson());
    }
}
