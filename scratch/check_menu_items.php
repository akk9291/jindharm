<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MenuItem;

$items = MenuItem::all();
foreach ($items as $i) {
    echo "ID: {$i->id} | Parent: " . ($i->parent_id ?: 'Root') . " | Label: " . $i->getLocalized('label') . " | URL: {$i->url}\n";
}
