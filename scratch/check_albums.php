<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Album;
use App\Models\AlbumPhoto;

$albums = Album::with('photos')->get();
echo "Total Albums: " . $albums->count() . "\n";
foreach($albums as $a) {
    echo "\nAlbum ID {$a->id}: '{$a->getLocalized('name')}' (Slug: {$a->slug}) | Cover: {$a->cover_image} | Photos: " . $a->photos->count() . "\n";
    foreach($a->photos as $p) {
        echo "   - ID {$p->id}: [{$p->photo_path}] {$p->getLocalized('caption')}\n";
    }
}
