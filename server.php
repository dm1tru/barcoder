<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Dm1tru\Barcoder\App;

$app = new App();
$app->runBarcodeServer();
