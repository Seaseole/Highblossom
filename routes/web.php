<?php

use App\Http\Controllers\SeoController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// Public SEO endpoints
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::get('/', [SiteController::class, 'home'])->name('home');

// Public Pages
Route::get('/services', [SiteController::class, 'services'])->name('services');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/quote', [SiteController::class, 'quote'])->name('quote');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'submitContact'])->name('contact.submit');

// Public Dynamic Routing
Route::livewire('/pages/{page:slug}', 'pages::pages.show')->name('pages.show');

// Public Blog
Route::livewire('/blog', 'pages::blog.index')->name('blog.index');
Route::get('/blog/feed', \App\Http\Controllers\BlogRssController::class)->name('blog.rss');
Route::get('/blog/sitemap', \App\Http\Controllers\BlogSitemapController::class)->name('blog.sitemap');
Route::livewire('/blog/{post:slug}', 'pages::blog.show')->name('blog.show');
Route::livewire('/blog/category/{category:slug}', 'pages::blog.category')->name('blog.category');
Route::livewire('/blog/tag/{tag:slug}', 'pages::blog.tag')->name('blog.tag');

// Booking Flow 
Route::middleware('throttle:6,1')->group(function () {
    Route::livewire('bookings/create', 'pages::bookings.create')->name('bookings.create');
    Route::get('/bookings/{booking}/confirmation', function (\App\Domains\Bookings\Models\Booking $booking) {
        return view('bookings.confirmation', ['booking' => $booking]);
    })->name('bookings.confirmation');
});

// Admin Portal
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Existing admin routes
    Route::livewire('pages', 'pages::admin.pages.index')->name('pages.index');
    Route::livewire('bookings', 'pages::admin.bookings.index')->name('bookings.index');
    Route::livewire('inspections', 'pages::admin.inspections.index')->name('inspections.index');
    Route::livewire('absences', 'pages::admin.absences.index')->name('absences.index');

    // Settings
    Route::livewire('settings/company', 'pages::admin.settings.company')->name('settings.company');
    Route::livewire('settings/smtp', 'pages::admin.settings.smtp')->name('settings.smtp');

    // Content Management
    Route::livewire('testimonials', 'pages::admin.testimonials.index')->name('testimonials.index');
    Route::livewire('testimonials/create', 'pages::admin.testimonials.edit')->name('testimonials.create');
    Route::livewire('testimonials/{id}/edit', 'pages::admin.testimonials.edit')->name('testimonials.edit');

    Route::livewire('services', 'pages::admin.services.index')->name('services.index');
    Route::livewire('services/create', 'pages::admin.services.edit')->name('services.create');
    Route::livewire('services/{id}/edit', 'pages::admin.services.edit')->name('services.edit');

    Route::livewire('gallery', 'pages::admin.gallery.index')->name('gallery.index');
    Route::livewire('gallery/create', 'pages::admin.gallery.edit')->name('gallery.create');
    Route::livewire('gallery/{id}/edit', 'pages::admin.gallery.edit')->name('gallery.edit');

    Route::livewire('contact-numbers', 'pages::admin.contact-numbers.index')->name('contact-numbers.index');
    Route::livewire('contact-numbers/create', 'pages::admin.contact-numbers.edit')->name('contact-numbers.create');
    Route::livewire('contact-numbers/{id}/edit', 'pages::admin.contact-numbers.edit')->name('contact-numbers.edit');

    Route::livewire('contact-messages', 'pages::admin.contact-messages.index')->name('contact-messages.index');

    // Blog Management
    Route::livewire('blog', 'pages::admin.blog.index')->name('blog.index');
    Route::livewire('blog/create', 'pages::admin.blog.editor')->name('blog.create');
    Route::livewire('blog/{id}/edit', 'pages::admin.blog.editor')->name('blog.edit');
    Route::livewire('blog/{id}/revisions', 'pages::admin.blog.revisions')->name('blog.revisions');
    Route::livewire('blog/{id}/analytics', 'pages::admin.blog.analytics')->name('blog.analytics');

    // Categories Management
    Route::livewire('categories', 'pages::admin.categories.index')->name('categories.index');
    Route::livewire('categories/create', 'pages::admin.categories.edit')->name('categories.create');
    Route::livewire('categories/{id}/edit', 'pages::admin.categories.edit')->name('categories.edit');

    // Tags Management
    Route::livewire('tags', 'pages::admin.tags.index')->name('tags.index');

    // SEO Management
    Route::livewire('seo/static-routes', 'pages::admin.seo.static-route-manager')->name('seo.static-routes');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
