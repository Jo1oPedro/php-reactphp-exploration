<?php

use React\EventLoop\TimerInterface;

require_once __DIR__ . "/../../vendor/autoload.php";

$loop = \React\EventLoop\Loop::get();

$counter1 = 0;

// Thats one way to cancel a timer
$timer1 = $loop->addPeriodicTimer(1, function () use (&$counter1, &$timer1, $loop) {
    $counter1++;

    if($counter1 === 5) {
        $loop->cancelTimer($timer1);
    }
    echo "Hello\n";
});

// Thats another way to cancel a timer, more clean
$counter2 = 0;
$loop->addPeriodicTimer(1, function (TimerInterface $timer2) use (&$counter2, $loop) {
    $counter2++;

    if($counter2 === 5) {
        $loop->cancelTimer($timer2);
    }
    echo "Hello2\n";
});

$loop->run();