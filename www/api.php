<?php

declare(strict_types=1);

use Dm1tru\Barcoder\App;

require 'vendor/autoload.php';
/*
var_dump($_SERVER['REQUEST_URI']);
var_dump($_SERVER);
var_dump($_GET);

var_dump($_REQUEST);
*/

try {
    $app = new App();
    $app->getApiRequest();
} catch (Exception $e) {
    var_dump($e->getMessage());
    var_dump($e->getCode());
}
