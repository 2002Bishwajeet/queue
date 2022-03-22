<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Utopia\Queue;

$connection = new Queue\Connection\RedisSwoole('redis');
$adapter = new Queue\Adapter\Swoole($connection, 12, 'swoole');
$server = new Queue\Server($adapter);
$server
    ->error(function ($th) {
        echo $th->getMessage() . PHP_EOL;
    })
    ->onStart(function() {
        echo "Queue Server started". PHP_EOL;
    })
    ->onJob(function (Queue\Job $job) {
        if (array_key_exists('stop', $job->getPayload())) {
            throw new Exception("Error Processing Request", 1);
        }
    })
    ->start();
