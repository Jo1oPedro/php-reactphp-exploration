<?php

use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;
use Reactphp\App\httpServer\CustomHeader;
use Reactphp\App\httpServer\Loggin;

require_once __DIR__ . "/../../vendor/autoload.php";

/*$loggin = function (ServerRequestInterface $request, callable $next) {
    echo 'Method: ' . $request->getMethod() . ' path: ' . $request->getUri()->getPath() . PHP_EOL;
    return $next($request);
};*/

$redirect = function(ServerRequestInterface $request, callable $next) {
    if($request->getUri()->getPath() === '/admin') {
        return new Response(301, ['Location' => '/']);
    }

    return $next($request);
};

$hello = function () {
    return new Response(
        200,
        [
            'Content-type' => 'application/json'
        ],
        'Hello world'
    );
};


$http = new HttpServer(
    //$loggin,
    new CustomHeader('X-Custom', 'foo'),
    new Loggin(),
    $redirect,
    $hello
);

$socket = new SocketServer('127.0.0.1:8000');
$http->listen($socket);