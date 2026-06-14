<?php

use App\Http\Controllers\PollController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// Public SEO endpoints
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::get('/', [SiteController::class, 'home'])->name('home');

// Public Pages
Route::get('/about-us', [SiteController::class, 'aboutUs'])->name('about-us');
Route::get('/services', [SiteController::class, 'services'])->name('services');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/gallery/{galleryImage}', [SiteController::class, 'galleryShow'])->name('gallery.show');
Route::get('/quote', [SiteController::class, 'quote'])->name('quote');
Route::post('/quote', [SiteController::class, 'submitQuote'])->middleware('throttle:3,1')->name('quote.submit');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'submitContact'])->middleware('throttle:3,1')->name('contact.submit');
Route::get('/terms', fn () => view('terms'))->name('terms');
Route::get('/privacy', fn () => view('privacy'))->name('privacy');

// Blog
Route::get('/blog', [SiteController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [SiteController::class, 'blogShow'])->name('blog.show');

// Poll API
Route::post('/api/content-blocks/poll/{poll}', [PollController::class, 'vote'])
    ->name('poll.vote')
    ->middleware('throttle:10,1');
Route::get('/api/content-blocks/poll/{poll}/results', [PollController::class, 'results'])->name('poll.results');

require __DIR__.'/settings.php';

Route::get('api/glass-types/{glassType}/sub-categories', [\App\Http\Controllers\Admin\GlassTypeController::class, 'getSubCategories'])->name('api.glass-types.sub-categories');
