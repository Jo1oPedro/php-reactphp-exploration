<?php

use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;

require_once __DIR__ . "/../../vendor/autoload.php";

$posts = [];
$http = new HttpServer(function (ServerRequestInterface $request) use (&$posts) {
    $path = $request->getUri()->getPath();
    if($path === '/store') {
        //$posts[] = $request->getParsedBody(); esse funciona quando um form-data é mandado e não um json
        $posts[] = json_decode((string) $request->getBody());
        return new Response(201);
    }

    return new Response(200, ['Content-type' => 'application/json'], json_encode($posts));
});
$socket = new SocketServer('127.0.0.1:8000');
$http->listen($socket);