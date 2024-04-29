<?php

declare(strict_types=1);

namespace Dm1tru\Barcoder;

use Dm1tru\Barcoder\Application\BarcodeServer;
use Dm1tru\Barcoder\Infrastructure\MysqlDeviceRepository;
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

        $server = new BarcodeServer($log, $deviceRepository, $barcodeRepository);
        $server->run();
    }
}
