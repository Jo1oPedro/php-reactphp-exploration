<?php

use React\Socket\ConnectionInterface;
use React\Socket\Connector;
use React\Stream\ReadableResourceStream;
use React\Stream\ThroughStream;
use React\Stream\WritableResourceStream;

require_once __DIR__ . "/../../vendor/autoload.php";

$input = new ReadableResourceStream(STDIN);
$output = new WritableResourceStream(STDOUT);

$connector = new Connector();
$connector->connect('127.0.0.1:8000')
    ->then(function(ConnectionInterface $connection) use ($input, $output) {
        /*$input->on('data', function ($data) use ($connection) {
            $connection->write($data);
        });*/
        //$throughStream = new ThroughStream('strtoupper');
        $input->pipe($connection)/*->pipe($throughStream)*/->pipe($output);
        /*$connection->on('data', function ($data) {
            //echo $data; Utilizar echo é basicamente escrever em um stream(STDOUT)
        });*/
        //$connection->pipe($output);
    },
    function(Exception $exception) {
        echo $exception->getMessage() . PHP_EOL;
    });

