<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;

$user = User::first();
if ($user) {
    Auth::login($user);
    echo "Logged in as: " . $user->name . " (" . $user->email . ")\n";
}

$menus = Menu::with('items.children')->get();
echo "Found " . $menus->count() . " menus.\n";
foreach ($menus as $m) {
    echo "Menu ID {$m->id}: '{$m->name}' [Location: {$m->location}]\n";
    foreach ($m->items as $item) {
        echo "  - Top Level: " . $item->getLocalized('label') . " ({$item->url})\n";
        foreach ($item->children as $child) {
            echo "      * Sub Item: " . $child->getLocalized('label') . " ({$child->url})\n";
        }
    }
}

// Test rendering admin view
$controller = new \App\Http\Controllers\Admin\AdminController();
$request = \Illuminate\Http\Request::create('/admin/menus', 'GET');
$view = $controller->menus($request);
echo "\nAdmin View compiled successfully! View name: " . $view->name() . "\n";

// Test saving updated structure
$itemsJson = json_encode([
    [
        'client_id' => 'item_1',
        'label_hi' => 'मुख्य पृष्ठ',
        'label_en' => 'Home',
        'url' => '/',
        'type' => 'home',
        'target' => '_self',
        'display_order' => 1
    ],
    [
        'client_id' => 'item_2',
        'label_hi' => 'ज्ञान भण्डार',
        'label_en' => 'Knowledge',
        'url' => '/granths',
        'type' => 'module',
        'target' => '_self',
        'display_order' => 2
    ],
    [
        'client_id' => 'item_3',
        'parent_client_id' => 'item_2',
        'label_hi' => 'अमृत प्रवचन',
        'label_en' => 'Pravachans',
        'url' => '/pravachans',
        'type' => 'module',
        'target' => '_self',
        'display_order' => 3
    ]
]);

$saveReq = \Illuminate\Http\Request::create('/admin/menus/save-structure', 'POST', [
    'menu_id' => 1,
    'menu_name' => 'मुख्य हेडर मेनू (Main Header Menu)',
    'locations' => ['header'],
    'items_data' => $itemsJson
]);

$saveRes = $controller->menuSaveStructure($saveReq);
echo "Save Structure Test: Status " . $saveRes->getStatusCode() . " -> Redirect to " . $saveRes->headers->get('Location') . "\n";


