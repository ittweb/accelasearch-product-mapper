<?php
use \AccelaSearch\ProductMapper\DataMapper\Api\Client;
use \AccelaSearch\ProductMapper\DataMapper\Api\Shop as ShopApi;

foreach (['vendor/autoload.php', __DIR__ . '/../../autoload.php', __DIR__ . '/../vendor/autoload.php', __DIR__ . '/vendor/autoload.php'] as $file) {
    if (file_exists($file)) {
        require $file;
        break;
    }
}

////////////////////////////////////////////////////////////////////////
// Sanity check
if ($argc < 3) {
    die(
        "Usage:" . PHP_EOL
      . "\t " . $argv[0] . " <api-key> <command> [<accelasearch-url>]" . PHP_EOL
      . "Where:" . PHP_EOL
      . "\tapi-key:          AccelaSearch API key" . PHP_EOL
      . "\taccelasearch-url: base URL for AccelaSearch APIs" . PHP_EOL
      . "\tcommand:          one of" . PHP_EOL
      . "\t\tindex <shop id>:    asks for a reindex for given shop" . PHP_EOL
    );
}

////////////////////////////////////////////////////////////////////////
// Reads parameters
$api_key = trim($argv[1]);
$command = trim($argv[3]);
$base_url = $argc >= 2 ? trim($argv[2]) : Client::DEFAULT_BASE_URL;

////////////////////////////////////////////////////////////////////////
// Connects to collector
$client = new Client($base_url, $api_key);
$shop_api = new ShopApi($client);

////////////////////////////////////////////////////////////////////////
// Executes command
if ($command === 'index') {
    $shop_api->index($argv[4]);
}
else {
    echo "Unknown command \"$command\"." . PHP_EOL;
}
