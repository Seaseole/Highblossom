<?php

use App\Http\Controllers\Admin\DashboardController;
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
Route::post('/quote', [SiteController::class, 'submitQuote'])->name('quote.submit');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'submitContact'])->name('contact.submit');

// Blog
Route::get('/blog', [SiteController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [SiteController::class, 'blogShow'])->name('blog.show');


// Booking Flow 
Route::middleware('throttle:6,1')->group(function () {
    // TODO: Migrate to controller + Blade view
    // Route::get('bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{booking}/confirmation', function (\App\Domains\Bookings\Models\Booking $booking) {
        return view('bookings.confirmation', ['booking' => $booking]);
    })->name('bookings.confirmation');
});

// Admin Portal
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');

    Route::get('inspections', [\App\Http\Controllers\Admin\InspectionController::class, 'index'])->name('inspections.index');
    Route::get('inspections/{inspection}', [\App\Http\Controllers\Admin\InspectionController::class, 'show'])->name('inspections.show');

    Route::get('absences', [\App\Http\Controllers\Admin\StaffAbsenceController::class, 'index'])->name('absences.index');
    Route::get('absences/{absence}', [\App\Http\Controllers\Admin\StaffAbsenceController::class, 'show'])->name('absences.show');

    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\CompanySettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\CompanySettingController::class, 'update'])->name('settings.update');

    // About Us
    Route::get('about-us', [\App\Http\Controllers\Admin\AboutUsController::class, 'edit'])->name('about-us.edit');
    Route::put('about-us', [\App\Http\Controllers\Admin\AboutUsController::class, 'update'])->name('about-us.update');

    // SMTP
    Route::get('smtp', [\App\Http\Controllers\Admin\SmtpSettingController::class, 'index'])->name('smtp.index');
    Route::put('smtp', [\App\Http\Controllers\Admin\SmtpSettingController::class, 'update'])->name('smtp.update');
    Route::post('smtp/test', [\App\Http\Controllers\Admin\SmtpSettingController::class, 'sendTest'])->name('smtp.test');

    // Content Management
    Route::get('testimonials', [\App\Http\Controllers\Admin\TestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('testimonials/create', [\App\Http\Controllers\Admin\TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('testimonials', [\App\Http\Controllers\Admin\TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('testimonials/{testimonial}/edit', [\App\Http\Controllers\Admin\TestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('testimonials/{testimonial}', [\App\Http\Controllers\Admin\TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('testimonials/{testimonial}', [\App\Http\Controllers\Admin\TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    Route::get('services', [\App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('services.index');
    Route::get('services/create', [\App\Http\Controllers\Admin\ServiceController::class, 'create'])->name('services.create');
    Route::post('services', [\App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('services.store');
    Route::get('services/{service}/edit', [\App\Http\Controllers\Admin\ServiceController::class, 'edit'])->name('services.edit');
    Route::put('services/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('services.update');
    Route::delete('services/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->name('services.destroy');

    // Blog Management
    Route::get('posts', [\App\Http\Controllers\Admin\PostController::class, 'index'])->name('posts.index');
    Route::get('posts/create', [\App\Http\Controllers\Admin\PostController::class, 'create'])->name('posts.create');
    Route::post('posts', [\App\Http\Controllers\Admin\PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/edit', [\App\Http\Controllers\Admin\PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'destroy'])->name('posts.destroy');

    Route::get('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('tags', [\App\Http\Controllers\Admin\TagController::class, 'index'])->name('tags.index');
    Route::get('tags/create', [\App\Http\Controllers\Admin\TagController::class, 'create'])->name('tags.create');
    Route::post('tags', [\App\Http\Controllers\Admin\TagController::class, 'store'])->name('tags.store');
    Route::get('tags/{tag}/edit', [\App\Http\Controllers\Admin\TagController::class, 'edit'])->name('tags.edit');
    Route::put('tags/{tag}', [\App\Http\Controllers\Admin\TagController::class, 'update'])->name('tags.update');
    Route::delete('tags/{tag}', [\App\Http\Controllers\Admin\TagController::class, 'destroy'])->name('tags.destroy');

    Route::get('gallery', [\App\Http\Controllers\Admin\GalleryController::class, 'index'])->name('gallery.index');
    Route::get('gallery/create', [\App\Http\Controllers\Admin\GalleryController::class, 'create'])->name('gallery.create');
    Route::post('gallery', [\App\Http\Controllers\Admin\GalleryController::class, 'store'])->name('gallery.store');
    Route::get('gallery/{item}/edit', [\App\Http\Controllers\Admin\GalleryController::class, 'edit'])->name('gallery.edit');
    Route::put('gallery/{item}', [\App\Http\Controllers\Admin\GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('gallery/{item}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->name('gallery.destroy');

    Route::get('gallery-categories', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'index'])->name('gallery-categories.index');
    Route::get('gallery-categories/create', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'create'])->name('gallery-categories.create');
    Route::post('gallery-categories', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'store'])->name('gallery-categories.store');
    Route::get('gallery-categories/{galleryCategory}/edit', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'edit'])->name('gallery-categories.edit');
    Route::put('gallery-categories/{galleryCategory}', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'update'])->name('gallery-categories.update');
    Route::delete('gallery-categories/{galleryCategory}', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'destroy'])->name('gallery-categories.destroy');

    Route::get('glass-types', [\App\Http\Controllers\Admin\GlassTypeController::class, 'index'])->name('glass-types.index');
    Route::get('glass-types/create', [\App\Http\Controllers\Admin\GlassTypeController::class, 'create'])->name('glass-types.create');
    Route::post('glass-types', [\App\Http\Controllers\Admin\GlassTypeController::class, 'store'])->name('glass-types.store');
    Route::get('glass-types/{glassType}/edit', [\App\Http\Controllers\Admin\GlassTypeController::class, 'edit'])->name('glass-types.edit');
    Route::put('glass-types/{glassType}', [\App\Http\Controllers\Admin\GlassTypeController::class, 'update'])->name('glass-types.update');
    Route::delete('glass-types/{glassType}', [\App\Http\Controllers\Admin\GlassTypeController::class, 'destroy'])->name('glass-types.destroy');

    Route::get('service-types', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'index'])->name('service-types.index');
    Route::get('service-types/create', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'create'])->name('service-types.create');
    Route::post('service-types', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'store'])->name('service-types.store');
    Route::get('service-types/{serviceType}/edit', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'edit'])->name('service-types.edit');
    Route::put('service-types/{serviceType}', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'update'])->name('service-types.update');
    Route::delete('service-types/{serviceType}', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'destroy'])->name('service-types.destroy');

    Route::get('contact-messages', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{message}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::delete('contact-messages/{message}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    // Quotes Management
    Route::get('quotes', [\App\Http\Controllers\Admin\QuoteController::class, 'index'])->name('quotes.index');
    Route::get('quotes/{quote}', [\App\Http\Controllers\Admin\QuoteController::class, 'show'])->name('quotes.show');
    Route::put('quotes/{quote}/status', [\App\Http\Controllers\Admin\QuoteController::class, 'updateStatus'])->name('quotes.updateStatus');
    Route::delete('quotes/{quote}', [\App\Http\Controllers\Admin\QuoteController::class, 'destroy'])->name('quotes.destroy');

    // SEO Management
    Route::get('seo/static-routes', [\App\Http\Controllers\Admin\SeoController::class, 'index'])->name('seo.static-routes');
    Route::get('seo/{id}/edit', [\App\Http\Controllers\Admin\SeoController::class, 'edit'])->name('seo.edit');
    Route::put('seo/{id}', [\App\Http\Controllers\Admin\SeoController::class, 'update'])->name('seo.update');

    // Access Control
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    
    Route::get('roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [\App\Http\Controllers\Admin\RoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [\App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{role}/edit', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy');

    // Media Library
    Route::get('media-library', [\App\Http\Controllers\Admin\MediaLibraryController::class, 'index'])->name('media-library.index');
    Route::post('media-library/upload', [\App\Http\Controllers\Admin\MediaLibraryController::class, 'upload'])->name('media-library.upload');

    // Image Upload (AJAX)
    Route::post('image-upload', [\App\Http\Controllers\Admin\ImageUploadController::class, 'upload'])->name('image-upload');

    // Video Upload (AJAX)
    Route::post('video-upload', [\App\Http\Controllers\Admin\VideoUploadController::class, 'upload'])->name('video-upload');

    // Profile
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/appearance', [\App\Http\Controllers\Admin\ProfileController::class, 'updateAppearance'])->name('profile.appearance.update');
    Route::put('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('profile/two-factor/enable', [\App\Http\Controllers\Admin\ProfileController::class, 'enableTwoFactor'])->name('profile.two-factor.enable');
    Route::post('profile/two-factor/disable', [\App\Http\Controllers\Admin\ProfileController::class, 'disableTwoFactor'])->name('profile.two-factor.disable');
    Route::delete('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
});

require __DIR__.'/settings.php';
