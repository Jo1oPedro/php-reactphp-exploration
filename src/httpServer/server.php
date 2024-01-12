<?php

use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;

require_once __DIR__ . "/../../vendor/autoload.php";

$server = new HttpServer(function (ServerRequestInterface $request) {
    echo 'Request to ' . $request->getUri();
    return new Response(
        200,
        ['Content-type' => 'text/plain'],
        'Hello, world'
    );
});
$socket = new SocketServer('127.0.0.1:8000');

$server->listen($socket);
