<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PasswordController as AdminPasswordController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Etalase
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/{variant}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{variant}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{variant}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('checkout.store');
Route::get('/pesanan/{order:order_code}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/cerita-kami', [PageController::class, 'story'])->name('story');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', fn (): Response => response(
    "User-agent: *\nAllow: /\nDisallow: /admin\n\nSitemap: ".route('sitemap')."\n",
    200,
    ['Content-Type' => 'text/plain']
))->name('robots');

/*
|--------------------------------------------------------------------------
| Panel Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.attempt');
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('products', AdminProductController::class)->except('show');
        Route::resource('categories', AdminCategoryController::class)->except('show');
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::get('settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::get('password', [AdminPasswordController::class, 'edit'])->name('password.edit');
        Route::put('password', [AdminPasswordController::class, 'update'])->name('password.update');
    });
});
