<?php

require_once __DIR__ . './../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

$time_start = microtime(true);

$client = new Client();

$requests = function() {
    for ($i = 0; $i < 1000; ++$i) {
        yield new Request('GET', "http://localhost:3000/test/{$i}");
    }
};
$pool = new Pool($client, $requests(), [
    'concurrency' => 500,
    'fulfilled' => function(ResponseInterface $response, $index) {
        echo sprintf("%5d: %s\n", $index, $response->getBody());
    },
]);

$promise = $pool->promise();
$promise->wait();

$time = microtime(true) - $time_start;
echo "{$time} 秒\n";

$mem = memory_get_peak_usage();
$mem = number_format($mem);
echo "Memory:{$mem} byte\n";