<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    PageController,
    CommunityController,
    ProductController,
    AlbumController,
    NewsletterController,
    PostController
};
use App\Http\Controllers\Admin\{
    SettingController as AdminSettingController,
    PageController as AdminPageController,
    CommunityController as AdminCommunityController,
    ProductController as AdminProductController,
    AlbumController as AdminAlbumController,
    CampaignController,
    PostController as AdminPostController,
    PartnerController as AdminPartnerController,
    SubscriberController
};

// ------------------ PÚBLICO ------------------
Route::get('/', HomeController::class)->name('home');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Páginas institucionales
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('page.show');

// Comunidades
Route::get('/comunidades', [CommunityController::class, 'index'])->name('comunidades.index');
Route::get('/comunidades/{community:slug}', [CommunityController::class, 'show'])->name('comunidades.show');

// Pestañas/Secciones dentro de una comunidad
Route::prefix('/comunidades/{community:slug}')->name('comunidad.')->group(function () {
    Route::get('/productos',     [CommunityController::class, 'products'])->name('productos');
    Route::get('/que-hacemos',   [CommunityController::class, 'whatWeDo'])->name('whatwedo');
    Route::get('/involucrate',   [CommunityController::class, 'takeAction'])->name('takeaction');
    Route::get('/donar',         [CommunityController::class, 'donate'])->name('donate');
});

// Productos
Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');
Route::get('/productos/{product:slug}', [ProductController::class, 'show'])->name('productos.show');

// Ferias / Álbumes
Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
Route::get('/albums/{album:slug}', [AlbumController::class, 'show'])->name('albums.show');

// Noticias
Route::get('/noticias', [PostController::class, 'index'])->name('noticias.index');
Route::get('/noticias/{noticia:slug}', [PostController::class, 'show'])->name('noticias.show');

// Contacto
Route::view('/contacto', 'pages.contacto')->name('contacto');

// -------- Arreglo Breeze: /dashboard -> /admin --------
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ------------------ ADMIN (protegido) ------------------
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');

    // Ajustes
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('settings/{any}', [AdminSettingController::class, 'update'])->name('settings.update');

    // CRUDs
    Route::resource('pages',       AdminPageController::class);
    Route::resource('communities', AdminCommunityController::class);
    Route::resource('products',    AdminProductController::class);
    Route::resource('albums',      AdminAlbumController::class);
    Route::resource('posts',       AdminPostController::class);
    Route::resource('partners',    AdminPartnerController::class);

    // Newsletter for Admin
    Route::prefix('newsletter')->name('newsletter.')->group(function () {
        Route::resource('subscribers', SubscriberController::class)->only(['index', 'destroy']);
        Route::resource('campaigns',   CampaignController::class);
        Route::post('campaigns/{campaign}/send-now', [CampaignController::class, 'sendNow'])->name('campaigns.send');
    });
});

require __DIR__ . '/auth.php';
