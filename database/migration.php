<?php

use Roocket\PocketCore\Request;

require_once __DIR__ . "./../vendor/autoload.php";

$app = new \Roocket\PocketCore\Application(dirname(__DIR__));

$app->db->migrations->applyMigrations();
