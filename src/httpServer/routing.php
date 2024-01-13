<?php

use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Http\Message\Response;

require_once __DIR__ . "/../../vendor/autoload.php";

$tasks = [
    'go to the market'
];

$listsTasks = function (ServerRequestInterface $request, callable $next) use (&$tasks) {
    if($request->getUri()->getPath() === '/tasks' && $request->getMethod() === 'GET') {
        return new Response(
            200,
            ['Content-type' => 'application/json'],
            json_encode($tasks)
        );
    }

    return $next($request);
};

$addNewTask = function (ServerRequestInterface $request, callable $next) use (&$tasks) {
    if($request->getUri()->getPath() === '/tasks' && $request->getMethod() === 'POST') {
        $newTask = $request->getParsedBody()['task'] ?? null;
        if($newTask) {
            $tasks[] = $newTask;
            return new Response(201);
        }
        return new Response(
            400,
            ['Content-type' => 'application/json'],
            json_encode(['error' => 'task field is required'])
        );
    }

    return $next($request);
};

$notFound = function () {
    return new Response(404);
};

$http = new HttpServer(...[
    $listsTasks,
    $addNewTask,
    $notFound
]);

$socket = new \React\Socket\SocketServer('127.0.0.1:8000');

$http->listen($socket);