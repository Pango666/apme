<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    PageController,
    CommunityController,
    ProductController,
    AlbumController,
    PostController
};
use App\Http\Controllers\Admin\{
    SettingController as AdminSettingController,
    PageController as AdminPageController,
    CommunityController as AdminCommunityController,
    ProductController as AdminProductController,
    AlbumController as AdminAlbumController,
    PostController as AdminPostController,
    PartnerController as AdminPartnerController
};

// ------------------ PÚBLICO ------------------
Route::get('/', HomeController::class)->name('home');

// Páginas institucionales
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('page.show');

// Comunidades
Route::get('/comunidades', [CommunityController::class, 'index'])->name('comunidades.index');
Route::get('/comunidades/{community:slug}', [CommunityController::class, 'show'])->name('comunidades.show');

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
})->middleware(['auth','verified'])->name('dashboard');

// ------------------ ADMIN (protegido) ------------------
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

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
});

require __DIR__.'/auth.php';
