<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\Item::all() as $item) {
    $slug = \Illuminate\Support\Str::slug($item->name);
    \App\Models\ItemImage::updateOrCreate(
        ['item_id' => $item->item_id],
        ['image' => "/images/items/{$slug}.jpg"]
    );
}
echo "Done!\n";
