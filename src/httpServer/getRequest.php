<?php

use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;

require_once __DIR__ . "/../../vendor/autoload.php";

$posts = require 'posts.php';
$server = new HttpServer(function (ServerRequestInterface $request) use ($posts) {
    $params = $request->getQueryParams();
    $tag = $params['tag'] ?? null;

    $filteredPosts = array_filter($posts, function (array $posts) use ($tag) {
        if(is_null($tag)) {
            return true;
        }

        return in_array($tag, $posts['tags']);
    });

    $page = $params['page'] ?? 1;
    $filteredPosts = array_chunk($filteredPosts, 2);
    $filteredPosts = $filteredPosts[$page - 1] ?? [];

    return new Response(
        200,
        ['Content-type' => /*'text/plain'*/ 'application/json'],
        json_encode($filteredPosts)
    );
});
$socket = new SocketServer('127.0.0.1:8000');

$server->listen($socket);
