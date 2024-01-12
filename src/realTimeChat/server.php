<?php

use React\EventLoop\Loop;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer;
use Reactphp\App\realTimeChat\ConnectionsPool;

require_once __DIR__ . '/../../vendor/autoload.php';

$loop = Loop::get();

$server = new SocketServer('127.0.0.1:8000');

$pool = new ConnectionsPool();

$server->on('connection', function (ConnectionInterface $connection)  use ($pool) {
    $pool->add($connection);
});