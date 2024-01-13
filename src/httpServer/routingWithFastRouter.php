<?php

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;
use function FastRoute\simpleDispatcher;

require_once __DIR__ . "/../../vendor/autoload.php";

$tasks = [
    'go to the market'
];

$listsTasks = function (ServerRequestInterface $request) use (&$tasks) {
    return new Response(
        200,
        ['Content-type' => 'application/json'],
        json_encode($tasks)
    );
};

$viewTask = function (ServerRequestInterface $request, int $id) use (&$tasks) {
    return isset($tasks[$id - 1])
        ? new Response(200, ['Content-type' => 'application/json'], json_encode($tasks[$id - 1]))
        : new Response(404);
};

$addNewTask = function (ServerRequestInterface $request) use (&$tasks) {
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
};

$dispatcher = simpleDispatcher(
    function(RouteCollector $routeCollector) use ($listsTasks, $addNewTask, $viewTask) {
        $routeCollector->get('/tasks', $listsTasks);
        $routeCollector->post('/tasks', $addNewTask);
        $routeCollector->get('/tasks/{id:\d+}', $viewTask);
    }
);

$http = new HttpServer(
    function (ServerRequestInterface $request) use ($dispatcher) {
        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return new Response(404);
            case Dispatcher::FOUND:
                return $routeInfo[1]($request, ...array_values($routeInfo[2]));
        }
    }
);

$socket = new SocketServer('127.0.0.1:8000');

$http->listen($socket);