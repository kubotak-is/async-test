<?hh

async function run(Vector $num): Awaitable<Vector>
{
    $handles = $num->map($i ==> \HH\Asio\curl_exec("http://localhost:3000/test/{$i}"));
    return await \HH\Asio\v($handles);
}

$time_start = microtime(true);
$num = Vector{};
for ($i = 0; $i < 100; ++$i) {
  $num->add($i);
}

$results = \HH\Asio\join(run($num));
foreach ($results as $result) {
  echo "{$result}";
}

$time = microtime(true) - $time_start;
echo "{$time} 秒\n";

$mem = memory_get_peak_usage();
$mem = number_format($mem);
echo "Memory:{$mem} byte\n";