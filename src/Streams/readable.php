<?php

use React\EventLoop\Loop;
use React\Stream\ReadableResourceStream;

require __DIR__ . "/../../vendor/autoload.php";

$loop = Loop::get();

// É possível ler 1 linha inteira por chunk
//$readable = new ReadableResourceStream(STDIN, $loop);

// É possível ler 1 caracter por chunk
$readable = new ReadableResourceStream(STDIN, $loop, 1);

/*$readable->on('data', function ($chunk) {
    echo $chunk . PHP_EOL;
});

É possível receber um evento de quando não há mais nada a ser lido
$readable->on('end', function () {
    echo 'Finished' . PHP_EOL;
});*/

$readable->on('data', function ($chunk) use ($readable, $loop) {
   echo $chunk . PHP_EOL;
   $readable->pause();
   
   $loop->addTimer(1, function () use ($readable) {
       $readable->resume();
   });
});

$loop->run();
