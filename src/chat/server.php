<?php

require_once __DIR__ . "/../../vendor/autoload.php";

$server = new \React\Socket\SocketServer('127.0.0.1:8000');
$server->on('connection', function(\React\Socket\ConnectionInterface $connection) {
    echo $connection->getRemoteAddress() . PHP_EOL;
    $connection->write('hello' . PHP_EOL);
    $connection->on('data', function ($data) use ($connection) {
        $connection->write($data);
    });
});
