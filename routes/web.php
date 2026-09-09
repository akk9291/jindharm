<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Frontend\SaintController;
use App\Http\Controllers\Frontend\ContentController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\PanchangController;
use App\Http\Controllers\Frontend\FestivalController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\QuoteController;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Saints & Guru Parampara
Route::get('/sant', [SaintController::class, 'index'])->name('sants.index');
Route::get('/sant/{slug}', [SaintController::class, 'show'])->name('sants.show');
Route::get('/sangh-vihar', [SaintController::class, 'sanghVihar'])->name('sangh-vihar');

// Pravachan
Route::get('/pravachan', [ContentController::class, 'pravachanIndex'])->name('pravachans.index');
Route::get('/pravachan/{slug}', [ContentController::class, 'pravachanShow'])->name('pravachans.show');

// Granth (Scriptures)
Route::get('/granth', [ContentController::class, 'granthIndex'])->name('granths.index');
Route::get('/granth/{slug}', [ContentController::class, 'granthShow'])->name('granths.show');

// Bhajan / Stuti
Route::get('/bhajan', [ContentController::class, 'bhajanIndex'])->name('bhajans.index');
Route::get('/bhajan/{slug}', [ContentController::class, 'bhajanShow'])->name('bhajans.show');

// News / Samachar
Route::get('/news', [ContentController::class, 'newsIndex'])->name('news.index');
Route::get('/news/{slug}', [ContentController::class, 'newsShow'])->name('news.show');

// Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// Galleries
Route::get('/gallery', [GalleryController::class, 'photos'])->name('gallery.index');
Route::get('/gallery/photos', [GalleryController::class, 'photos'])->name('gallery.photos');
Route::get('/gallery/album/{id}', [GalleryController::class, 'albumShow'])->name('gallery.album.show');
Route::get('/gallery/videos', [GalleryController::class, 'videos'])->name('gallery.videos');
Route::get('/gallery/pdfs', [GalleryController::class, 'pdfs'])->name('gallery.pdfs');

// Panchang & Festivals
Route::get('/jain-panchang', [PanchangController::class, 'index'])->name('panchang.index');
Route::get('/jain-festivals', [FestivalController::class, 'index'])->name('festivals.index');
Route::get('/jain-festivals/{slug}', [FestivalController::class, 'show'])->name('festivals.show');

// Daily Quotes / Suvichar Archive
Route::get('/suvichar', [QuoteController::class, 'index'])->name('suvichar.index');
Route::get('/suvichar/{id}', [QuoteController::class, 'show'])->name('suvichar.show');

// Universal Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// CMS Pages & Contact
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact/send', [PageController::class, 'sendContact'])->name('contact.send');

// Friendly Route Aliases & Redirects
Route::redirect('/sants', '/sant');
Route::redirect('/pravachans', '/pravachan');
Route::redirect('/granths', '/granth');
Route::redirect('/bhajans', '/bhajan');
Route::redirect('/panchang', '/jain-panchang');
Route::redirect('/festivals', '/jain-festivals');
Route::get('/page/{slug}', fn($slug) => redirect('/pages/' . $slug));

// Admin Authentication Routes
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Dashboard (All logged-in active users can view dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Saints & Guru Parampara
    Route::get('/saints', [AdminController::class, 'saints'])->middleware('can:saint.view,guru.view');
    Route::post('/saints/store', [AdminController::class, 'saintStore'])->middleware('can:saint.create,saint.edit,guru.create,guru.edit');
    Route::post('/saints/sort', [AdminController::class, 'saintSort'])->middleware('can:saint.edit,guru.edit');
    Route::get('/saints/delete/{id}', [AdminController::class, 'saintDelete'])->middleware('can:saint.delete,guru.delete');

    // Vihars
    Route::get('/vihar', [AdminController::class, 'vihars'])->middleware('can:vihar.view');
    Route::post('/vihar/store', [AdminController::class, 'viharStore'])->middleware('can:vihar.create,vihar.edit');
    Route::get('/vihar/delete/{id}', [AdminController::class, 'viharDelete'])->middleware('can:vihar.delete');

    // Content Types
    Route::get('/content-types', [AdminController::class, 'contentTypes'])->middleware('can:content_type.view');
    Route::post('/content-types/store', [AdminController::class, 'contentTypeStore'])->middleware('can:content_type.create,content_type.edit');
    Route::get('/content-types/delete/{id}', [AdminController::class, 'contentTypeDelete'])->middleware('can:content_type.delete');

    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->middleware('can:category.view');
    Route::post('/categories/store', [AdminController::class, 'categoryStore'])->middleware('can:category.create,category.edit');
    Route::get('/categories/delete/{id}', [AdminController::class, 'categoryDelete'])->middleware('can:category.delete');

    // Contents (Editor)
    Route::get('/contents', [AdminController::class, 'contents'])->middleware('can:content.view');
    Route::get('/contents/create', [AdminController::class, 'contentsCreate'])->middleware('can:content.create');
    Route::post('/contents/store', [AdminController::class, 'contentsStore'])->middleware('can:content.create,content.edit');
    Route::get('/contents/edit/{id}', [AdminController::class, 'contentsEdit'])->middleware('can:content.edit');
    Route::get('/contents/delete/{id}', [AdminController::class, 'contentsDelete'])->middleware('can:content.delete');

    // Media Library
    Route::get('/media', [AdminController::class, 'media'])->middleware('can:media.view');
    Route::post('/media/upload', [AdminController::class, 'mediaUpload'])->middleware('can:media.upload');
    Route::post('/media/folder', [AdminController::class, 'mediaFolderStore'])->middleware('can:media.upload,media.edit');
    Route::get('/media/delete/{id}', [AdminController::class, 'mediaDelete'])->middleware('can:media.delete');

    // Albums & Photo Gallery
    Route::get('/albums', [AdminController::class, 'albums'])->middleware('can:gallery.view');
    Route::post('/albums/store', [AdminController::class, 'albumStore'])->middleware('can:gallery.create,gallery.edit');
    Route::get('/albums/delete/{id}', [AdminController::class, 'albumDelete'])->middleware('can:gallery.delete');
    Route::post('/albums/photos/upload', [AdminController::class, 'albumPhotoUpload'])->middleware('can:gallery.create,gallery.edit');

    // Events
    Route::get('/events', [AdminController::class, 'events'])->middleware('can:event.view');
    Route::post('/events/store', [AdminController::class, 'eventStore'])->middleware('can:event.create,event.edit');
    Route::get('/events/delete/{id}', [AdminController::class, 'eventDelete'])->middleware('can:event.delete');

    // Panchang
    Route::get('/panchang', [AdminController::class, 'panchang'])->middleware('can:panchang.view');
    Route::post('/panchang/store', [AdminController::class, 'panchangStore'])->middleware('can:panchang.create,panchang.edit');

    // Festivals
    Route::get('/festivals', [AdminController::class, 'festivals'])->middleware('can:festival.view');
    Route::post('/festivals/store', [AdminController::class, 'festivalStore'])->middleware('can:festival.create,festival.edit');
    Route::get('/festivals/delete/{id}', [AdminController::class, 'festivalDelete'])->middleware('can:festival.delete');

    // Pages
    Route::get('/pages', [AdminController::class, 'pages'])->middleware('can:page.view');
    Route::post('/pages/store', [AdminController::class, 'pageStore'])->middleware('can:page.create,page.edit');
    Route::get('/pages/delete/{id}', [AdminController::class, 'pageDelete'])->middleware('can:page.delete');

    // Menu Builder
    Route::get('/menus', [AdminController::class, 'menus'])->middleware('can:menu.view');
    Route::post('/menus/store', [AdminController::class, 'menuStore'])->middleware('can:menu.create,menu.edit');
    Route::post('/menus/save-structure', [AdminController::class, 'menuSaveStructure'])->middleware('can:menu.create,menu.edit');
    Route::get('/menus/delete/{id}', [AdminController::class, 'menuDelete'])->middleware('can:menu.delete');
    Route::post('/menus/item/store', [AdminController::class, 'menuItemStore'])->middleware('can:menu.create,menu.edit');

    // Homepage Builder
    Route::get('/homepage', [AdminController::class, 'homepage'])->middleware('can:homepage.view');
    Route::post('/homepage/sort', [AdminController::class, 'homepageSort'])->middleware('can:homepage.edit');
    Route::post('/homepage/toggle', [AdminController::class, 'homepageToggle'])->middleware('can:homepage.edit');

    // Daily Quotes & Suvichar Archive
    Route::get('/quotes', [AdminController::class, 'quotes'])->middleware('can:settings.view');
    Route::post('/quotes/store', [AdminController::class, 'quoteStore'])->middleware('can:settings.manage');
    Route::get('/quotes/delete/{id}', [AdminController::class, 'quoteDelete'])->middleware('can:settings.manage');
    Route::get('/quotes/feature/{id}', [AdminController::class, 'quoteToggleFeatured'])->middleware('can:settings.manage');

    // Sangh Information & Live Vihar Status Management
    Route::get('/sangh-vihar', [AdminController::class, 'sanghVihar'])->middleware('can:settings.view');
    Route::post('/sangh-vihar/store', [AdminController::class, 'sanghViharStore'])->middleware('can:settings.manage');

    // Website Settings
    Route::get('/settings', [AdminController::class, 'settings'])->middleware('can:settings.view');
    Route::post('/settings/store', [AdminController::class, 'settingsStore'])->middleware('can:settings.manage');

    // Activity Logs
    Route::get('/logs', [AdminController::class, 'logs'])->middleware('can:log.view');

    // --- RBAC Routes ---
    
    // Users Management
    Route::prefix('users')->middleware('can:user.view')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/store', [UserController::class, 'store'])->name('users.store')->middleware('can:user.create,user.edit');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('can:user.delete');
        Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password')->middleware('can:user.edit');
    });

    // Roles Management
    Route::prefix('roles')->middleware('can:role.view')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/store', [RoleController::class, 'store'])->name('roles.store')->middleware('can:role.create,role.edit');
        Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('can:role.delete');
        Route::post('/clone/{id}', [RoleController::class, 'clone'])->name('roles.clone')->middleware('can:role.create');
    });

    // Permissions Management
    Route::prefix('permissions')->middleware('can:permission.view')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store')->middleware('can:permission.create,permission.edit');
        Route::get('/delete/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('can:permission.delete');
    });
});
