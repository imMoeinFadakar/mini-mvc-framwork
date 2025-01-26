<?php

use Roocket\PocketCore\Request;

require_once __DIR__ . "./../vendor/autoload.php";

$app = new \Roocket\PocketCore\Application(dirname(__DIR__));

$app->router
    ->setRouterFile(__DIR__ . '/../routes/web.php')
    ->setRouterFile(__DIR__ . '/../routes/api.php');

$app->run();
