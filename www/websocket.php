<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Dm1tru\Barcoder\App;


try {
    $app = new App();
    $app->runWebsocketServer();
} catch (Exception $e) {
    var_dump($e->getMessage());
}
