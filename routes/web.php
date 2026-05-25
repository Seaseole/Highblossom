<?php

// use App\Models\Booking;
use App\Http\Controllers\Admin\AboutUsController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryCategoryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GlassSubCategoryController;
use App\Http\Controllers\Admin\GlassTypeController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\InspectionController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Admin\SmtpSettingController;
use App\Http\Controllers\Admin\StaffAbsenceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoUploadController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;
use Lubusin\Decomposer\Controllers\DecomposerController;

// Public SEO endpoints
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::get('/', [SiteController::class, 'home'])->name('home');
// Route::get('/logo-demo', fn() => view('logo-demo'))->name('logo.demo');

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
// Route::middleware('throttle:6,1')->group(function () {
//    // TODO: Migrate to controller + Blade view
//    Route::get('/bookings/{booking}/confirmation', function (Booking $booking) {
//        return view('bookings.confirmation', ['booking' => $booking]);
//    })->name('bookings.confirmation');
// });

// Admin Portal
Route::middleware(['auth', 'verified', 'can:access admin panel'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('bookings', [BookingController::class, 'index'])->middleware('can:view bookings')->name('bookings.index');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->middleware('can:view bookings')->name('bookings.show');
    Route::patch('bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->middleware('can:update bookings')->name('bookings.update-status');
    Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->middleware('can:update bookings')->name('bookings.destroy');

    Route::get('inspections', [InspectionController::class, 'index'])->middleware('can:view inspections')->name('inspections.index');
    Route::get('inspections/{inspection}', [InspectionController::class, 'show'])->middleware('can:view inspections')->name('inspections.show');

    Route::get('absences', [StaffAbsenceController::class, 'index'])->middleware('can:manage absences')->name('absences.index');
    Route::get('absences/{absence}', [StaffAbsenceController::class, 'show'])->middleware('can:manage absences')->name('absences.show');

    // Settings
    Route::get('settings', [CompanySettingController::class, 'index'])->middleware('can:view settings')->name('settings.index');
    Route::put('settings', [CompanySettingController::class, 'update'])->middleware('can:update settings')->name('settings.update');

    // About Us
    Route::get('about-us', [AboutUsController::class, 'edit'])->middleware('can:manage pages')->name('about-us.edit');
    Route::put('about-us', [AboutUsController::class, 'update'])->middleware('can:manage pages')->name('about-us.update');

    // SMTP
    Route::get('smtp', [SmtpSettingController::class, 'index'])->middleware('can:view settings')->name('smtp.index');
    Route::put('smtp', [SmtpSettingController::class, 'update'])->middleware('can:update settings')->name('smtp.update');
    Route::post('smtp/test', [SmtpSettingController::class, 'sendTest'])->middleware('can:update settings')->name('smtp.test');

    // Content Management
    Route::get('testimonials', [TestimonialController::class, 'index'])->middleware('can:manage testimonials')->name('testimonials.index');
    Route::get('testimonials/create', [TestimonialController::class, 'create'])->middleware('can:manage testimonials')->name('testimonials.create');
    Route::post('testimonials', [TestimonialController::class, 'store'])->middleware('can:manage testimonials')->name('testimonials.store');
    Route::get('testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->middleware('can:manage testimonials')->name('testimonials.edit');
    Route::put('testimonials/{testimonial}', [TestimonialController::class, 'update'])->middleware('can:manage testimonials')->name('testimonials.update');
    Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->middleware('can:manage testimonials')->name('testimonials.destroy');

    Route::get('services', [ServiceController::class, 'index'])->middleware('can:view services')->name('services.index');
    Route::get('services/create', [ServiceController::class, 'create'])->middleware('can:manage services')->name('services.create');
    Route::post('services', [ServiceController::class, 'store'])->middleware('can:manage services')->name('services.store');
    Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->middleware('can:manage services')->name('services.edit');
    Route::put('services/{service}', [ServiceController::class, 'update'])->middleware('can:manage services')->name('services.update');
    Route::delete('services/{service}', [ServiceController::class, 'destroy'])->middleware('can:manage services')->name('services.destroy');

    // Blog Management
    Route::get('posts', [PostController::class, 'index'])->middleware('can:view blog')->name('posts.index');
    Route::get('posts/create', [PostController::class, 'create'])->middleware('can:create blog')->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->middleware('can:create blog')->name('posts.store');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->middleware('can:update blog')->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->middleware('can:update blog')->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->middleware('can:delete blog')->name('posts.destroy');

    Route::get('categories', [CategoryController::class, 'index'])->middleware('can:view blog')->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->middleware('can:create blog')->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->middleware('can:create blog')->name('categories.store');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->middleware('can:update blog')->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->middleware('can:update blog')->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->middleware('can:delete blog')->name('categories.destroy');

    Route::get('tags', [TagController::class, 'index'])->middleware('can:view blog')->name('tags.index');
    Route::get('tags/create', [TagController::class, 'create'])->middleware('can:create blog')->name('tags.create');
    Route::post('tags', [TagController::class, 'store'])->middleware('can:create blog')->name('tags.store');
    Route::get('tags/{tag}/edit', [TagController::class, 'edit'])->middleware('can:update blog')->name('tags.edit');
    Route::put('tags/{tag}', [TagController::class, 'update'])->middleware('can:update blog')->name('tags.update');
    Route::delete('tags/{tag}', [TagController::class, 'destroy'])->middleware('can:delete blog')->name('tags.destroy');

    Route::get('staff', [StaffController::class, 'index'])->middleware('can:manage settings')->name('staff.index');
    Route::get('staff/create', [StaffController::class, 'create'])->middleware('can:manage settings')->name('staff.create');
    Route::post('staff', [StaffController::class, 'store'])->middleware('can:manage settings')->name('staff.store');
    Route::get('staff/{staff}/edit', [StaffController::class, 'edit'])->middleware('can:manage settings')->name('staff.edit');
    Route::put('staff/{staff}', [StaffController::class, 'update'])->middleware('can:manage settings')->name('staff.update');
    Route::delete('staff/{staff}', [StaffController::class, 'destroy'])->middleware('can:manage settings')->name('staff.destroy');

    Route::get('partners', [PartnerController::class, 'index'])->middleware('can:manage settings')->name('partners.index');
    Route::get('partners/create', [PartnerController::class, 'create'])->middleware('can:manage settings')->name('partners.create');
    Route::post('partners', [PartnerController::class, 'store'])->middleware('can:manage settings')->name('partners.store');
    Route::get('partners/{partner}/edit', [PartnerController::class, 'edit'])->middleware('can:manage settings')->name('partners.edit');
    Route::put('partners/{partner}', [PartnerController::class, 'update'])->middleware('can:manage settings')->name('partners.update');
    Route::delete('partners/{partner}', [PartnerController::class, 'destroy'])->middleware('can:manage settings')->name('partners.destroy');

    Route::get('gallery', [GalleryController::class, 'index'])->middleware('can:view gallery')->name('gallery.index');
    Route::get('gallery/settings', [CompanySettingController::class, 'gallerySettings'])->middleware('can:manage gallery')->name('gallery-settings.index');
    Route::put('gallery/settings', [CompanySettingController::class, 'updateGallerySettings'])->middleware('can:manage gallery')->name('gallery-settings.update');
    Route::get('gallery/create', [GalleryController::class, 'create'])->middleware('can:manage gallery')->name('gallery.create');
    Route::post('gallery', [GalleryController::class, 'store'])->middleware('can:manage gallery')->name('gallery.store');
    Route::get('gallery/{item}/edit', [GalleryController::class, 'edit'])->middleware('can:manage gallery')->name('gallery.edit');
    Route::put('gallery/{item}', [GalleryController::class, 'update'])->middleware('can:manage gallery')->name('gallery.update');
    Route::delete('gallery/{item}', [GalleryController::class, 'destroy'])->middleware('can:manage gallery')->name('gallery.destroy');

    Route::get('gallery-categories', [GalleryCategoryController::class, 'index'])->middleware('can:view gallery')->name('gallery-categories.index');
    Route::get('gallery-categories/create', [GalleryCategoryController::class, 'create'])->middleware('can:manage gallery')->name('gallery-categories.create');
    Route::post('gallery-categories', [GalleryCategoryController::class, 'store'])->middleware('can:manage gallery')->name('gallery-categories.store');
    Route::get('gallery-categories/{galleryCategory}/edit', [GalleryCategoryController::class, 'edit'])->middleware('can:manage gallery')->name('gallery-categories.edit');
    Route::put('gallery-categories/{galleryCategory}', [GalleryCategoryController::class, 'update'])->middleware('can:manage gallery')->name('gallery-categories.update');
    Route::delete('gallery-categories/{galleryCategory}', [GalleryCategoryController::class, 'destroy'])->middleware('can:manage gallery')->name('gallery-categories.destroy');

    Route::get('glass-types', [GlassTypeController::class, 'index'])->middleware('can:view services')->name('glass-types.index');
    Route::get('glass-types/create', [GlassTypeController::class, 'create'])->middleware('can:manage services')->name('glass-types.create');
    Route::post('glass-types', [GlassTypeController::class, 'store'])->middleware('can:manage services')->name('glass-types.store');
    Route::get('glass-types/{glassType}/edit', [GlassTypeController::class, 'edit'])->middleware('can:manage services')->name('glass-types.edit');
    Route::put('glass-types/{glassType}', [GlassTypeController::class, 'update'])->middleware('can:manage services')->name('glass-types.update');
    Route::delete('glass-types/{glassType}', [GlassTypeController::class, 'destroy'])->middleware('can:manage services')->name('glass-types.destroy');

    // Glass Sub-Categories
    Route::get('glass-sub-categories', [GlassSubCategoryController::class, 'index'])->middleware('can:manage services')->name('glass-sub-categories.index');
    Route::get('glass-sub-categories/create', [GlassSubCategoryController::class, 'create'])->middleware('can:manage services')->name('glass-sub-categories.create');
    Route::post('glass-sub-categories', [GlassSubCategoryController::class, 'store'])->middleware('can:manage services')->name('glass-sub-categories.store');
    Route::get('glass-sub-categories/{glassSubCategory}/edit', [GlassSubCategoryController::class, 'edit'])->middleware('can:manage services')->name('glass-sub-categories.edit');
    Route::put('glass-sub-categories/{glassSubCategory}', [GlassSubCategoryController::class, 'update'])->middleware('can:manage services')->name('glass-sub-categories.update');
    Route::delete('glass-sub-categories/{glassSubCategory}', [GlassSubCategoryController::class, 'destroy'])->middleware('can:manage services')->name('glass-sub-categories.destroy');
    Route::patch('glass-sub-categories/{glassSubCategory}/toggle-status', [GlassSubCategoryController::class, 'toggleStatus'])->name('glass-sub-categories.toggle-status');
    Route::get('glass-sub-categories/by-glass-type/{glassTypeId}', [GlassSubCategoryController::class, 'getByGlassType'])->name('glass-sub-categories.by-glass-type');
    Route::put('glass-sub-categories/reorder', [GlassSubCategoryController::class, 'reorder'])->name('glass-sub-categories.reorder');

    Route::get('service-types', [ServiceTypeController::class, 'index'])->middleware('can:view services')->name('service-types.index');
    Route::get('service-types/create', [ServiceTypeController::class, 'create'])->middleware('can:manage services')->name('service-types.create');
    Route::post('service-types', [ServiceTypeController::class, 'store'])->middleware('can:manage services')->name('service-types.store');
    Route::get('service-types/{serviceType}/edit', [ServiceTypeController::class, 'edit'])->middleware('can:manage services')->name('service-types.edit');
    Route::put('service-types/{serviceType}', [ServiceTypeController::class, 'update'])->middleware('can:manage services')->name('service-types.update');
    Route::delete('service-types/{serviceType}', [ServiceTypeController::class, 'destroy'])->middleware('can:manage services')->name('service-types.destroy');

    Route::get('contact-messages', [ContactMessageController::class, 'index'])->middleware('can:view contact messages')->name('contact-messages.index');
    Route::get('contact-messages/{message}', [ContactMessageController::class, 'show'])->middleware('can:view contact messages')->name('contact-messages.show');
    Route::patch('contact-messages/{message}/mark-read', [ContactMessageController::class, 'markAsRead'])->middleware('can:view contact messages')->name('contact-messages.mark-read');
    Route::delete('contact-messages/{message}', [ContactMessageController::class, 'destroy'])->middleware('can:view contact messages')->name('contact-messages.destroy');

    // Quotes Management
    Route::get('quotes', [QuoteController::class, 'index'])->middleware('can:view bookings')->name('quotes.index');
    Route::get('quotes/{quote}', [QuoteController::class, 'show'])->middleware('can:view bookings')->name('quotes.show');
    Route::put('quotes/{quote}/status', [QuoteController::class, 'updateStatus'])->middleware('can:update bookings')->name('quotes.updateStatus');
    Route::delete('quotes/{quote}', [QuoteController::class, 'destroy'])->middleware('can:update bookings')->name('quotes.destroy');

    // SEO Management
    Route::get('seo/static-routes', [App\Http\Controllers\Admin\SeoController::class, 'index'])->middleware('can:manage seo')->name('seo.static-routes');
    Route::get('seo/create', [App\Http\Controllers\Admin\SeoController::class, 'create'])->middleware('can:manage seo')->name('seo.create');
    Route::post('seo', [App\Http\Controllers\Admin\SeoController::class, 'store'])->middleware('can:manage seo')->name('seo.store');
    Route::get('seo/{id}/edit', [App\Http\Controllers\Admin\SeoController::class, 'edit'])->middleware('can:manage seo')->name('seo.edit');
    Route::put('seo/{id}', [App\Http\Controllers\Admin\SeoController::class, 'update'])->middleware('can:manage seo')->name('seo.update');

    // Access Control
    Route::get('users', [UserController::class, 'index'])->middleware('can:manage users')->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->middleware('can:manage users')->name('users.create');
    Route::post('users', [UserController::class, 'store'])->middleware('can:manage users')->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->middleware('can:manage users')->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('can:manage users')->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('can:manage users')->name('users.destroy');

    Route::get('roles', [RoleController::class, 'index'])->middleware('can:manage roles')->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->middleware('can:manage roles')->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->middleware('can:manage roles')->name('roles.store');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->middleware('can:manage roles')->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->middleware('can:manage roles')->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->middleware('can:manage roles')->name('roles.destroy');

    // Media Library
    Route::get('media-library', [MediaLibraryController::class, 'index'])->name('media-library.index');
    Route::post('media-library/upload', [MediaLibraryController::class, 'upload'])->name('media-library.upload');
    Route::get('media-library/{image}', [MediaLibraryController::class, 'show'])->name('media-library.show');
    Route::delete('media-library/{image}', [MediaLibraryController::class, 'destroy'])->name('media-library.destroy');

    // Image Upload (AJAX)
    Route::post('image-upload', [ImageUploadController::class, 'upload'])->name('image-upload');

    // Video Upload (AJAX)
    Route::post('video-upload', [VideoUploadController::class, 'upload'])->name('video-upload');

    // Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/appearance', [ProfileController::class, 'updateAppearance'])->name('profile.appearance.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('profile/two-factor/enable', [ProfileController::class, 'enableTwoFactor'])->name('profile.two-factor.enable');
    Route::post('profile/two-factor/confirm', [ProfileController::class, 'confirmTwoFactor'])->name('profile.two-factor.confirm');
    Route::post('profile/two-factor/disable', [ProfileController::class, 'disableTwoFactor'])->name('profile.two-factor.disable');
    Route::get('profile/two-factor/recovery-codes', [ProfileController::class, 'showRecoveryCodes'])->name('profile.two-factor.recovery-codes');
    Route::post('profile/two-factor/recovery-codes', [ProfileController::class, 'regenerateRecoveryCodes'])->name('profile.two-factor.regenerate-recovery-codes');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('decompose', [DecomposerController::class, 'index'])->name('decompose');
});

// Public API endpoints for quote form
Route::get('api/glass-types/{glassType}/sub-categories', [GlassTypeController::class, 'getSubCategories'])->name('api.glass-types.sub-categories');

Route::passkeys();

Route::middleware(['auth', 'verified', 'can:access admin panel'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
});

require __DIR__.'/settings.php';
