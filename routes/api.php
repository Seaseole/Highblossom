<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GalleryCategoryController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\TaxonomyController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Staff App API
|--------------------------------------------------------------------------
|
| Token-authenticated JSON API for the Highblossom staff mobile app. It lives
| under the `staff` prefix so it can never collide with the public, web-group
| `/api/*` endpoints (bookings/availability, bookings/calendar, blog/search).
| Every data route sits behind `auth:sanctum` and is gated by the same Spatie
| `can:` abilities the web admin panel uses.
|
*/

Route::prefix('staff')->group(function () {
    Route::post('login', [AuthController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('api.staff.login');

    // Second factor for accounts with 2FA enabled. Throttled per IP, and the
    // challenge itself is revoked after five wrong codes.
    Route::post('two-factor-challenge', [AuthController::class, 'twoFactorChallenge'])
        ->middleware('throttle:6,1')
        ->name('api.staff.two-factor-challenge');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me'])->name('api.staff.me');
        Route::post('logout', [AuthController::class, 'logout'])->name('api.staff.logout');

        // Dashboard aggregate counts
        Route::get('dashboard', DashboardController::class)->middleware('can:view dashboard')->name('api.staff.dashboard');

        // Bookings
        Route::get('bookings', [BookingController::class, 'index'])->middleware('can:view bookings')->name('api.staff.bookings.index');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])->middleware('can:view bookings')->whereNumber('booking')->name('api.staff.bookings.show');
        Route::patch('bookings/{booking}', [BookingController::class, 'update'])->middleware('can:update bookings')->whereNumber('booking')->name('api.staff.bookings.update');
        Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->middleware('can:update bookings')->whereNumber('booking')->name('api.staff.bookings.update-status');

        // Inspections (appointments)
        Route::get('inspections', [InspectionController::class, 'index'])->middleware('can:view inspections')->name('api.staff.inspections.index');
        Route::post('inspections', [InspectionController::class, 'store'])->middleware('can:update inspections')->name('api.staff.inspections.store');
        Route::get('inspections/{inspection}', [InspectionController::class, 'show'])->middleware('can:view inspections')->whereNumber('inspection')->name('api.staff.inspections.show');
        Route::put('inspections/{inspection}', [InspectionController::class, 'update'])->middleware('can:update inspections')->whereNumber('inspection')->name('api.staff.inspections.update');
        Route::delete('inspections/{inspection}', [InspectionController::class, 'destroy'])->middleware('can:update inspections')->whereNumber('inspection')->name('api.staff.inspections.destroy');
        Route::post('inspections/{inspection}/notes', [InspectionController::class, 'storeNote'])->middleware('can:update inspections')->whereNumber('inspection')->name('api.staff.inspections.notes.store');
        Route::patch('inspections/{inspection}/notes/{note}/done', [InspectionController::class, 'toggleNoteDone'])->middleware('can:update inspections')->whereNumber('inspection')->whereNumber('note')->name('api.staff.inspections.notes.done');
        Route::delete('inspections/{inspection}/notes/{note}', [InspectionController::class, 'destroyNote'])->middleware('can:update inspections')->whereNumber('inspection')->whereNumber('note')->name('api.staff.inspections.notes.destroy');

        // Quotes (gated by the bookings abilities, mirroring the web admin)
        Route::get('quotes', [QuoteController::class, 'index'])->middleware('can:view bookings')->name('api.staff.quotes.index');
        Route::get('quotes/{quote}', [QuoteController::class, 'show'])->middleware('can:view bookings')->whereNumber('quote')->name('api.staff.quotes.show');
        Route::put('quotes/{quote}/status', [QuoteController::class, 'updateStatus'])->middleware('can:update bookings')->whereNumber('quote')->name('api.staff.quotes.update-status');
        Route::delete('quotes/{quote}', [QuoteController::class, 'destroy'])->middleware('can:update bookings')->whereNumber('quote')->name('api.staff.quotes.destroy');

        // Contact messages (inbox triage)
        Route::get('contact-messages', [ContactMessageController::class, 'index'])->middleware('can:view contact messages')->name('api.staff.contact-messages.index');
        Route::get('contact-messages/{message}', [ContactMessageController::class, 'show'])->middleware('can:view contact messages')->whereNumber('message')->name('api.staff.contact-messages.show');
        Route::patch('contact-messages/{message}/read', [ContactMessageController::class, 'markAsRead'])->middleware('can:view contact messages')->whereNumber('message')->name('api.staff.contact-messages.mark-read');
        Route::delete('contact-messages/{message}', [ContactMessageController::class, 'destroy'])->middleware('can:view contact messages')->whereNumber('message')->name('api.staff.contact-messages.destroy');

        // Testimonials
        Route::get('testimonials', [TestimonialController::class, 'index'])->middleware('can:manage testimonials')->name('api.staff.testimonials.index');
        Route::post('testimonials', [TestimonialController::class, 'store'])->middleware('can:manage testimonials')->name('api.staff.testimonials.store');
        Route::get('testimonials/{testimonial}', [TestimonialController::class, 'show'])->middleware('can:manage testimonials')->whereNumber('testimonial')->name('api.staff.testimonials.show');
        Route::put('testimonials/{testimonial}', [TestimonialController::class, 'update'])->middleware('can:manage testimonials')->whereNumber('testimonial')->name('api.staff.testimonials.update');
        Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->middleware('can:manage testimonials')->whereNumber('testimonial')->name('api.staff.testimonials.destroy');

        // Blog: posts (read + moderate) and read-only taxonomy.
        // Post binds by slug app-wide, so pin these to `{post:id}` for a stable numeric key.
        Route::get('posts', [PostController::class, 'index'])->middleware('can:view blog')->name('api.staff.posts.index');
        Route::get('posts/{post:id}', [PostController::class, 'show'])->middleware('can:view blog')->whereNumber('post')->name('api.staff.posts.show');
        Route::patch('posts/{post:id}/status', [PostController::class, 'updateStatus'])->middleware('can:update blog')->whereNumber('post')->name('api.staff.posts.update-status');
        Route::delete('posts/{post:id}', [PostController::class, 'destroy'])->middleware('can:delete blog')->whereNumber('post')->name('api.staff.posts.destroy');
        Route::get('categories', [TaxonomyController::class, 'categories'])->middleware('can:view blog')->name('api.staff.categories.index');
        Route::get('tags', [TaxonomyController::class, 'tags'])->middleware('can:view blog')->name('api.staff.tags.index');

        // Staff directory (path is `team` to avoid /api/staff/staff; gated by manage settings)
        Route::get('team', [StaffController::class, 'index'])->middleware('can:manage settings')->name('api.staff.team.index');
        Route::post('team', [StaffController::class, 'store'])->middleware('can:manage settings')->name('api.staff.team.store');
        Route::get('team/{staff}', [StaffController::class, 'show'])->middleware('can:manage settings')->whereNumber('staff')->name('api.staff.team.show');
        Route::put('team/{staff}', [StaffController::class, 'update'])->middleware('can:manage settings')->whereNumber('staff')->name('api.staff.team.update');
        Route::delete('team/{staff}', [StaffController::class, 'destroy'])->middleware('can:manage settings')->whereNumber('staff')->name('api.staff.team.destroy');

        // Partners (gated by manage settings, mirroring the web admin)
        Route::get('partners', [PartnerController::class, 'index'])->middleware('can:manage settings')->name('api.staff.partners.index');
        Route::post('partners', [PartnerController::class, 'store'])->middleware('can:manage settings')->name('api.staff.partners.store');
        Route::get('partners/{partner}', [PartnerController::class, 'show'])->middleware('can:manage settings')->whereNumber('partner')->name('api.staff.partners.show');
        Route::put('partners/{partner}', [PartnerController::class, 'update'])->middleware('can:manage settings')->whereNumber('partner')->name('api.staff.partners.update');
        Route::delete('partners/{partner}', [PartnerController::class, 'destroy'])->middleware('can:manage settings')->whereNumber('partner')->name('api.staff.partners.destroy');

        // Gallery (curated public images, incl. multipart upload)
        Route::get('gallery', [GalleryController::class, 'index'])->middleware('can:view gallery')->name('api.staff.gallery.index');
        Route::post('gallery', [GalleryController::class, 'store'])->middleware('can:manage gallery')->name('api.staff.gallery.store');
        Route::get('gallery/{item}', [GalleryController::class, 'show'])->middleware('can:view gallery')->whereNumber('item')->name('api.staff.gallery.show');
        Route::put('gallery/{item}', [GalleryController::class, 'update'])->middleware('can:manage gallery')->whereNumber('item')->name('api.staff.gallery.update');
        Route::delete('gallery/{item}', [GalleryController::class, 'destroy'])->middleware('can:manage gallery')->whereNumber('item')->name('api.staff.gallery.destroy');
        Route::get('gallery-categories', [GalleryCategoryController::class, 'index'])->middleware('can:view gallery')->name('api.staff.gallery-categories.index');
    });
});
