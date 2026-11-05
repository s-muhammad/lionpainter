<?php

use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\QuoteRequestController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/portfolio', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/portfolio/{project}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/service-areas', [PageController::class, 'serviceAreas'])->name('service-areas');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('contact.store');

Route::get('/quote', [PageController::class, 'quote'])->name('quote');
Route::post('/quote', [QuoteController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('quote.store');

Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post}', [PostController::class, 'show'])->name('posts.show');

Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Admin panel (Persian / RTL)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware('admin')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('services', AdminServiceController::class)->except('show');
        Route::resource('projects', AdminProjectController::class)->except('show');
        Route::resource('posts', AdminPostController::class)->except('show');
        Route::resource('testimonials', TestimonialController::class)->except(['show', 'create']);
        Route::resource('faqs', FaqController::class)->except(['show', 'create']);

        Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
        Route::patch('messages/{message}/read', [ContactMessageController::class, 'toggleRead'])->name('messages.read');
        Route::delete('messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('quotes', [QuoteRequestController::class, 'index'])->name('quotes.index');
        Route::get('quotes/{quote}', [QuoteRequestController::class, 'show'])->name('quotes.show');
        Route::patch('quotes/{quote}', [QuoteRequestController::class, 'update'])->name('quotes.update');
        Route::delete('quotes/{quote}', [QuoteRequestController::class, 'destroy'])->name('quotes.destroy');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
