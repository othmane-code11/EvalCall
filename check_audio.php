<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$e = App\Models\Evaluation::find(3);
echo 'Audio: ' . $e->audio . PHP_EOL;
echo 'Asset: ' . asset('storage/' . $e->audio) . PHP_EOL;
