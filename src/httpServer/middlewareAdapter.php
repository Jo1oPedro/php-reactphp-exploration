<?php

use FriendsOfReact\Http\Middleware\Psr15Adapter\GroupedPSR15Middleware;
use FriendsOfReact\Http\Middleware\Psr15Adapter\PSR15Middleware;
use Middlewares\ClientIp;
use Middlewares\Redirect;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;

require_once __DIR__ . "/../../vendor/autoload.php";

$http = new HttpServer(
    (new GroupedPSR15Middleware())
        ->withMiddleware(new ClientIp())
        ->withMiddleware(new Redirect(['/admin' => '/'])),
    function (ServerRequestInterface $request, callable $next) {
        echo 'Client IP:' . $request->getAttribute('client-ip') . PHP_EOL;
        return $next($request);
    },
    function (ServerRequestInterface $request) {
        if($request->getUri()->getPath() === '/') {
            return new Response(200, ['Content-type' => 'text-plain'], 'Hello world');
        }
        if($request->getUri()->getPath() === '/admin') {
            return new Response(200, ['Content-type' => 'text-plain'], 'cascata123');
        }
    }
);

$socket = new SocketServer('127.0.0.1:8000');

$http->listen($socket);