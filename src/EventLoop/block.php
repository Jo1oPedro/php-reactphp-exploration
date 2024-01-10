<?php

require_once __DIR__ . "/../../vendor/autoload.php";

$loop = \React\EventLoop\Loop::get();

$loop->addPeriodicTimer(1, function () {
    echo "Hello\n";
});

$loop->addTimer(1, function () {
    sleep(5);
});

$loop->run();