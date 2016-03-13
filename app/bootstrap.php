<?php

require_once '../vendor/autoload.php';

$container = new \Slim\Container;

$container['db'] = function () {
    return new PDO('mysql:host=127.0.0.1;dbname=rest', 'homestead', 'secret');
};

$app = new \App\App($container);
$app->add(new \App\Middleware\JsonResponseMiddleware);

require_once 'routes.php';
