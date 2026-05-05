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
Route::post('/quote', [SiteController::class, 'submitQuote'])->middleware('throttle:3,1')->name('quote.submit');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'submitContact'])->middleware('throttle:3,1')->name('contact.submit');

// Blog
Route::get('/blog', [SiteController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [SiteController::class, 'blogShow'])->name('blog.show');


// Booking Flow
Route::middleware('throttle:6,1')->group(function () {
    // TODO: Migrate to controller + Blade view
    Route::get('/bookings/{booking}/confirmation', function (\App\Domains\Bookings\Models\Booking $booking) {
        return view('bookings.confirmation', ['booking' => $booking]);
    })->name('bookings.confirmation');
});

// Admin Portal
Route::middleware(['auth', 'verified', 'can:access admin panel'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->middleware('can:view bookings')->name('bookings.index');
    Route::get('bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->middleware('can:view bookings')->name('bookings.show');

    Route::get('inspections', [\App\Http\Controllers\Admin\InspectionController::class, 'index'])->middleware('can:view inspections')->name('inspections.index');
    Route::get('inspections/{inspection}', [\App\Http\Controllers\Admin\InspectionController::class, 'show'])->middleware('can:view inspections')->name('inspections.show');

    Route::get('absences', [\App\Http\Controllers\Admin\StaffAbsenceController::class, 'index'])->middleware('can:manage absences')->name('absences.index');
    Route::get('absences/{absence}', [\App\Http\Controllers\Admin\StaffAbsenceController::class, 'show'])->middleware('can:manage absences')->name('absences.show');

    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\CompanySettingController::class, 'index'])->middleware('can:view settings')->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\CompanySettingController::class, 'update'])->middleware('can:update settings')->name('settings.update');

    // About Us
    Route::get('about-us', [\App\Http\Controllers\Admin\AboutUsController::class, 'edit'])->middleware('can:manage pages')->name('about-us.edit');
    Route::put('about-us', [\App\Http\Controllers\Admin\AboutUsController::class, 'update'])->middleware('can:manage pages')->name('about-us.update');

    // SMTP
    Route::get('smtp', [\App\Http\Controllers\Admin\SmtpSettingController::class, 'index'])->middleware('can:view settings')->name('smtp.index');
    Route::put('smtp', [\App\Http\Controllers\Admin\SmtpSettingController::class, 'update'])->middleware('can:update settings')->name('smtp.update');
    Route::post('smtp/test', [\App\Http\Controllers\Admin\SmtpSettingController::class, 'sendTest'])->middleware('can:update settings')->name('smtp.test');

    // Content Management
    Route::get('testimonials', [\App\Http\Controllers\Admin\TestimonialController::class, 'index'])->middleware('can:manage testimonials')->name('testimonials.index');
    Route::get('testimonials/create', [\App\Http\Controllers\Admin\TestimonialController::class, 'create'])->middleware('can:manage testimonials')->name('testimonials.create');
    Route::post('testimonials', [\App\Http\Controllers\Admin\TestimonialController::class, 'store'])->middleware('can:manage testimonials')->name('testimonials.store');
    Route::get('testimonials/{testimonial}/edit', [\App\Http\Controllers\Admin\TestimonialController::class, 'edit'])->middleware('can:manage testimonials')->name('testimonials.edit');
    Route::put('testimonials/{testimonial}', [\App\Http\Controllers\Admin\TestimonialController::class, 'update'])->middleware('can:manage testimonials')->name('testimonials.update');
    Route::delete('testimonials/{testimonial}', [\App\Http\Controllers\Admin\TestimonialController::class, 'destroy'])->middleware('can:manage testimonials')->name('testimonials.destroy');

    Route::get('services', [\App\Http\Controllers\Admin\ServiceController::class, 'index'])->middleware('can:view services')->name('services.index');
    Route::get('services/create', [\App\Http\Controllers\Admin\ServiceController::class, 'create'])->middleware('can:manage services')->name('services.create');
    Route::post('services', [\App\Http\Controllers\Admin\ServiceController::class, 'store'])->middleware('can:manage services')->name('services.store');
    Route::get('services/{service}/edit', [\App\Http\Controllers\Admin\ServiceController::class, 'edit'])->middleware('can:manage services')->name('services.edit');
    Route::put('services/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'update'])->middleware('can:manage services')->name('services.update');
    Route::delete('services/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->middleware('can:manage services')->name('services.destroy');

    // Blog Management
    Route::get('posts', [\App\Http\Controllers\Admin\PostController::class, 'index'])->middleware('can:view blog')->name('posts.index');
    Route::get('posts/create', [\App\Http\Controllers\Admin\PostController::class, 'create'])->middleware('can:create blog')->name('posts.create');
    Route::post('posts', [\App\Http\Controllers\Admin\PostController::class, 'store'])->middleware('can:create blog')->name('posts.store');
    Route::get('posts/{post}/edit', [\App\Http\Controllers\Admin\PostController::class, 'edit'])->middleware('can:update blog')->name('posts.edit');
    Route::put('posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'update'])->middleware('can:update blog')->name('posts.update');
    Route::delete('posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'destroy'])->middleware('can:delete blog')->name('posts.destroy');

    Route::get('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->middleware('can:view blog')->name('categories.index');
    Route::get('categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->middleware('can:create blog')->name('categories.create');
    Route::post('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->middleware('can:create blog')->name('categories.store');
    Route::get('categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->middleware('can:update blog')->name('categories.edit');
    Route::put('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->middleware('can:update blog')->name('categories.update');
    Route::delete('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->middleware('can:delete blog')->name('categories.destroy');

    Route::get('tags', [\App\Http\Controllers\Admin\TagController::class, 'index'])->middleware('can:view blog')->name('tags.index');
    Route::get('tags/create', [\App\Http\Controllers\Admin\TagController::class, 'create'])->middleware('can:create blog')->name('tags.create');
    Route::post('tags', [\App\Http\Controllers\Admin\TagController::class, 'store'])->middleware('can:create blog')->name('tags.store');
    Route::get('tags/{tag}/edit', [\App\Http\Controllers\Admin\TagController::class, 'edit'])->middleware('can:update blog')->name('tags.edit');
    Route::put('tags/{tag}', [\App\Http\Controllers\Admin\TagController::class, 'update'])->middleware('can:update blog')->name('tags.update');
    Route::delete('tags/{tag}', [\App\Http\Controllers\Admin\TagController::class, 'destroy'])->middleware('can:delete blog')->name('tags.destroy');

    Route::get('gallery', [\App\Http\Controllers\Admin\GalleryController::class, 'index'])->middleware('can:view gallery')->name('gallery.index');
    Route::get('gallery/create', [\App\Http\Controllers\Admin\GalleryController::class, 'create'])->middleware('can:manage gallery')->name('gallery.create');
    Route::post('gallery', [\App\Http\Controllers\Admin\GalleryController::class, 'store'])->middleware('can:manage gallery')->name('gallery.store');
    Route::get('gallery/{item}/edit', [\App\Http\Controllers\Admin\GalleryController::class, 'edit'])->middleware('can:manage gallery')->name('gallery.edit');
    Route::put('gallery/{item}', [\App\Http\Controllers\Admin\GalleryController::class, 'update'])->middleware('can:manage gallery')->name('gallery.update');
    Route::delete('gallery/{item}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->middleware('can:manage gallery')->name('gallery.destroy');

    Route::get('gallery-categories', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'index'])->middleware('can:view gallery')->name('gallery-categories.index');
    Route::get('gallery-categories/create', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'create'])->middleware('can:manage gallery')->name('gallery-categories.create');
    Route::post('gallery-categories', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'store'])->middleware('can:manage gallery')->name('gallery-categories.store');
    Route::get('gallery-categories/{galleryCategory}/edit', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'edit'])->middleware('can:manage gallery')->name('gallery-categories.edit');
    Route::put('gallery-categories/{galleryCategory}', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'update'])->middleware('can:manage gallery')->name('gallery-categories.update');
    Route::delete('gallery-categories/{galleryCategory}', [\App\Http\Controllers\Admin\GalleryCategoryController::class, 'destroy'])->middleware('can:manage gallery')->name('gallery-categories.destroy');

    Route::get('glass-types', [\App\Http\Controllers\Admin\GlassTypeController::class, 'index'])->middleware('can:view services')->name('glass-types.index');
    Route::get('glass-types/create', [\App\Http\Controllers\Admin\GlassTypeController::class, 'create'])->middleware('can:manage services')->name('glass-types.create');
    Route::post('glass-types', [\App\Http\Controllers\Admin\GlassTypeController::class, 'store'])->middleware('can:manage services')->name('glass-types.store');
    Route::get('glass-types/{glassType}/edit', [\App\Http\Controllers\Admin\GlassTypeController::class, 'edit'])->middleware('can:manage services')->name('glass-types.edit');
    Route::put('glass-types/{glassType}', [\App\Http\Controllers\Admin\GlassTypeController::class, 'update'])->middleware('can:manage services')->name('glass-types.update');
    Route::delete('glass-types/{glassType}', [\App\Http\Controllers\Admin\GlassTypeController::class, 'destroy'])->middleware('can:manage services')->name('glass-types.destroy');

    Route::get('service-types', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'index'])->middleware('can:view services')->name('service-types.index');
    Route::get('service-types/create', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'create'])->middleware('can:manage services')->name('service-types.create');
    Route::post('service-types', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'store'])->middleware('can:manage services')->name('service-types.store');
    Route::get('service-types/{serviceType}/edit', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'edit'])->middleware('can:manage services')->name('service-types.edit');
    Route::put('service-types/{serviceType}', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'update'])->middleware('can:manage services')->name('service-types.update');
    Route::delete('service-types/{serviceType}', [\App\Http\Controllers\Admin\ServiceTypeController::class, 'destroy'])->middleware('can:manage services')->name('service-types.destroy');

    Route::get('contact-messages', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->middleware('can:view contact messages')->name('contact-messages.index');
    Route::get('contact-messages/{message}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->middleware('can:view contact messages')->name('contact-messages.show');
    Route::delete('contact-messages/{message}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->middleware('can:view contact messages')->name('contact-messages.destroy');

    // Quotes Management
    Route::get('quotes', [\App\Http\Controllers\Admin\QuoteController::class, 'index'])->middleware('can:view bookings')->name('quotes.index');
    Route::get('quotes/{quote}', [\App\Http\Controllers\Admin\QuoteController::class, 'show'])->middleware('can:view bookings')->name('quotes.show');
    Route::put('quotes/{quote}/status', [\App\Http\Controllers\Admin\QuoteController::class, 'updateStatus'])->middleware('can:update bookings')->name('quotes.updateStatus');
    Route::delete('quotes/{quote}', [\App\Http\Controllers\Admin\QuoteController::class, 'destroy'])->middleware('can:update bookings')->name('quotes.destroy');

    // SEO Management
    Route::get('seo/static-routes', [\App\Http\Controllers\Admin\SeoController::class, 'index'])->middleware('can:manage seo')->name('seo.static-routes');
    Route::get('seo/create', [\App\Http\Controllers\Admin\SeoController::class, 'create'])->middleware('can:manage seo')->name('seo.create');
    Route::post('seo', [\App\Http\Controllers\Admin\SeoController::class, 'store'])->middleware('can:manage seo')->name('seo.store');
    Route::get('seo/{id}/edit', [\App\Http\Controllers\Admin\SeoController::class, 'edit'])->middleware('can:manage seo')->name('seo.edit');
    Route::put('seo/{id}', [\App\Http\Controllers\Admin\SeoController::class, 'update'])->middleware('can:manage seo')->name('seo.update');

    // Access Control
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->middleware('can:manage users')->name('users.index');
    Route::get('users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->middleware('can:manage users')->name('users.create');
    Route::post('users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->middleware('can:manage users')->name('users.store');
    Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->middleware('can:manage users')->name('users.edit');
    Route::put('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->middleware('can:manage users')->name('users.update');
    Route::delete('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->middleware('can:manage users')->name('users.destroy');
    
    Route::get('roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->middleware('can:manage roles')->name('roles.index');
    Route::get('roles/create', [\App\Http\Controllers\Admin\RoleController::class, 'create'])->middleware('can:manage roles')->name('roles.create');
    Route::post('roles', [\App\Http\Controllers\Admin\RoleController::class, 'store'])->middleware('can:manage roles')->name('roles.store');
    Route::get('roles/{role}/edit', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])->middleware('can:manage roles')->name('roles.edit');
    Route::put('roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])->middleware('can:manage roles')->name('roles.update');
    Route::delete('roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])->middleware('can:manage roles')->name('roles.destroy');

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

Route::middleware(['auth', 'verified', 'can:access admin panel'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
});

require __DIR__.'/settings.php';
