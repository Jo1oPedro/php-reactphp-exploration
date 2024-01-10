<?php

require_once __DIR__ . "/../../vendor/autoload.php";

$loop = \React\EventLoop\Loop::get();

$loop->addTimer(1, function () {
   echo "After timer\n";
});

echo "Before timer\n";

$loop->run();