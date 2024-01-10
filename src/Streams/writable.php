<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$loop = \React\EventLoop\Loop::get();

$readable = new \React\Stream\ReadableResourceStream(STDIN, $loop);
$writable = new \React\Stream\WritableResourceStream(STDOUT, $loop);
$toUpper = new \React\Stream\ThroughStream(function ($chunk) {
    return strtoupper($chunk);
});
// Dessa forma, estamos pegando o output de readable e utilizando como input para writable, o que é um pipe = |
/*$readable->on('data', function ($chunk) use ($writable) {
    $writable->write($chunk);
});

$writable->write('Hello' . PHP_EOL);*/

// Nós podemos rescrever o código anterior com o método pipe
// Permite abstrair ter de escutar a diferentes evento e manualmente processar o fluxo de dados
$readable->pipe($toUpper)->pipe($writable);

$loop->run();