<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$gcal = app(\App\Services\GoogleCalendarService::class);
$reflection = new ReflectionClass($gcal);
$method = $reflection->getMethod('serviceAccountJson');
$method->setAccessible(true);
$json = $method->invoke($gcal);
$creds = json_decode($json, true);
$pk = $creds['private_key'] ?? '';

echo "Raw PK starts with: " . substr($pk, 0, 40) . "\n";
$pk1 = str_replace('\\n', "\n", $pk);
echo "str_replace PK starts with: " . substr($pk1, 0, 40) . "\n";

$res = openssl_sign('test', $sig, $pk1, 'sha256WithRSAEncryption');
var_dump($res);
while($msg = openssl_error_string()) {
    echo "SSL ERROR: $msg\n";
}
