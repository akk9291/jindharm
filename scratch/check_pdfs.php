<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Content;

$pdfs = Content::whereNotNull('pdf_files')->get();
echo "Found " . $pdfs->count() . " contents with PDFs:\n";
foreach($pdfs as $p) {
    echo "ID {$p->id}: " . $p->getLocalized('title') . " | PDF files: " . json_encode($p->pdf_files, JSON_UNESCAPED_UNICODE) . "\n";
}
