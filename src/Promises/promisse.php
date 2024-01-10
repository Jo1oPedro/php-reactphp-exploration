<?php

use React\Promise\PromiseInterface;

require_once __DIR__ . "/../../vendor/autoload.php";

function http($url, $method): PromiseInterface
{
    $response = 'data';
    $deferred = new \React\Promise\Deferred();


    if($response) {
        $deferred->resolve($response);
    } else {
        $deferred->reject(new Exception('No response'));
    }

    return $deferred->promise();
}

http('http://google.com', 'GET')
    ->then(
        function ($response) {
            //throw new Exception('error');
            return strtoupper($response);
        }
    )
    ->catch(function (Exception $exception) {
        echo $exception->getMessage() . PHP_EOL;
    });
    /* Podemos utilizar then or catch
     * ->then(
        function($response) {
            echo $response . PHP_EOL;
        },
        function (Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    );*/