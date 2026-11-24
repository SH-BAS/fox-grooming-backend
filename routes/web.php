<?php

use Illuminate\Support\Facades\Route;

// web routes
Route::name('web.')->group(function () {
    Route::get('/', [App\Http\Controllers\Web\WebController::class, 'index'])->name('index');
    Route::get('/services', [App\Http\Controllers\Web\WebController::class, 'services'])->name('services');
    Route::get('/courses', [App\Http\Controllers\Web\WebController::class, 'courses'])->name('courses');
    Route::get('/about', [App\Http\Controllers\Web\WebController::class, 'about'])->name('about');
    Route::get('/franchise', [App\Http\Controllers\Web\WebController::class, 'franchise'])->name('franchise');
    Route::get('/franchise/{franchise}', [App\Http\Controllers\Web\WebController::class, 'franchiseDetails'])->name('franchise.details');
    Route::post('/check-zip', [App\Http\Controllers\Web\WebController::class, 'checkZip'])->name('checkZip');
    Route::get('/pricing', [App\Http\Controllers\Web\WebController::class, 'pricing'])->name('pricing');
    Route::get('/contact', [App\Http\Controllers\Web\WebController::class, 'contact'])->name('contact');
    Route::post('/contact-submit', [App\Http\Controllers\Web\WebController::class, 'contactSubmit'])->name('contact.submit');
    Route::post('/newsletter-subscribe', [App\Http\Controllers\Web\WebController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

    // Auth Routes
    Route::get('/login', [App\Http\Controllers\Web\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Web\AuthController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Web\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Web\AuthController::class, 'register']);
    Route::post('/logout', [App\Http\Controllers\Web\AuthController::class, 'logout'])->name('logout');
    Route::get('/verify-email', [App\Http\Controllers\Web\AuthController::class, 'showVerifyForm'])->name('verify.email');
    Route::post('/verify-email', [App\Http\Controllers\Web\AuthController::class, 'verify']);
    Route::post('/resend-otp', [App\Http\Controllers\Web\AuthController::class, 'resendOtp'])->name('resend.otp');
    Route::get('/forgot-password', [App\Http\Controllers\Web\AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Web\AuthController::class, 'sendPasswordResetOtp'])->name('password.email');
    Route::get('/forgot-password/verify', [App\Http\Controllers\Web\AuthController::class, 'showPasswordResetVerifyForm'])->name('password.verify.form');
    Route::post('/forgot-password/verify', [App\Http\Controllers\Web\AuthController::class, 'verifyPasswordResetOtp'])->name('password.verify');
    Route::post('/forgot-password/resend', [App\Http\Controllers\Web\AuthController::class, 'resendPasswordResetOtp'])->name('password.resend');
    Route::get('/reset-password', [App\Http\Controllers\Web\AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
    Route::post('/reset-password', [App\Http\Controllers\Web\AuthController::class, 'resetPassword'])->name('password.reset');

    // Booking Flow (Public part)
    Route::get('/book-now', [App\Http\Controllers\Web\UserBookingController::class, 'selectFranchise'])->name('book.franchise');
    Route::get('/search-franchises', [App\Http\Controllers\Web\UserBookingController::class, 'searchFranchises'])->name('search.franchises');

    // Authenticated User Routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/profile', [App\Http\Controllers\Web\ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/update', [App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [App\Http\Controllers\Web\ProfileController::class, 'updatePassword'])->name('profile.password');

        Route::get('/pets/get-breeds/{category}', [App\Http\Controllers\Web\PetController::class, 'getBreeds'])->name('pets.breeds');
        Route::delete('/pets/image/{image}', [App\Http\Controllers\Web\PetController::class, 'deleteImage'])->name('pets.deleteImage');
        Route::resource('pets', App\Http\Controllers\Web\PetController::class);
        
        // User Bookings
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/bookings', [App\Http\Controllers\Web\UserBookingController::class, 'index'])->name('bookings.index');
            Route::get('/bookings/create/{franchise}', [App\Http\Controllers\Web\UserBookingController::class, 'create'])->name('bookings.create');
            Route::post('/bookings/store', [App\Http\Controllers\Web\UserBookingController::class, 'store'])->name('bookings.store');
            Route::get('/bookings/get-slots', [App\Http\Controllers\Web\UserBookingController::class, 'getTimeSlots'])->name('bookings.getTimeSlots');
            Route::get('/bookings/get-services', [App\Http\Controllers\Web\UserBookingController::class, 'getServices'])->name('bookings.getServices');
            Route::post('/bookings/{booking}/review', [App\Http\Controllers\Web\UserBookingController::class, 'storeReview'])->name('bookings.review');
            Route::get('/bookings/{booking}', [App\Http\Controllers\Web\UserBookingController::class, 'show'])->name('bookings.show');
        });
    });
});

// admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\LoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/bookings/{booking}/invoice', [App\Http\Controllers\Admin\BookingController::class, 'invoice'])->name('bookings.invoice');
        Route::put('/bookings/{booking}/base-price', [App\Http\Controllers\Admin\BookingController::class, 'updateBasePrice'])->name('bookings.updateBasePrice');
        Route::put('/bookings/{booking}/out-of-area-fee', [App\Http\Controllers\Admin\BookingController::class, 'updateOutOfAreaFee'])->name('bookings.updateOutOfAreaFee');
        Route::put('/bookings/{booking}/status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
        Route::post('/bookings/{booking}/add-service', [App\Http\Controllers\Admin\BookingController::class, 'addService'])->name('bookings.addService');
        Route::put('/bookings/services/{bookedService}', [App\Http\Controllers\Admin\BookingController::class, 'updateBookedService'])->name('bookings.updateService');
        Route::delete('/bookings/services/{bookedService}', [App\Http\Controllers\Admin\BookingController::class, 'deleteBookedService'])->name('bookings.deleteService');
        Route::post('/bookings/{booking}/upload-document', [App\Http\Controllers\Admin\BookingController::class, 'uploadDocument'])->name('bookings.uploadDocument');
        Route::put('/bookings/{booking}/assign', [App\Http\Controllers\Admin\BookingController::class, 'assignStaff'])->name('bookings.assignStaff');
        Route::get('/bookings/schedule/view', [App\Http\Controllers\Admin\BookingController::class, 'schedule'])->name('bookings.schedule');
        Route::get('/bookings/schedule/data', [App\Http\Controllers\Admin\BookingController::class, 'scheduleData'])->name('bookings.scheduleData');
        Route::get('/bookings/time-slots', [App\Http\Controllers\Admin\BookingController::class, 'getTimeSlots'])->name('bookings.getTimeSlots');
        Route::patch('/bookings/{booking}/update-date', [App\Http\Controllers\Admin\BookingController::class, 'updateDate'])->name('bookings.updateDate');

        // Profile Routes
        Route::get('/profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'password'])->name('profile.password');
        Route::put('/profile/password/update', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password.update');

        Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
        Route::resource('admins', App\Http\Controllers\Admin\AdminController::class);
        Route::patch('/staff/{staff}/status', [App\Http\Controllers\Admin\StaffController::class, 'toggleStatus'])->name('staff.status');
        Route::post('/staff/{id}/restore', [App\Http\Controllers\Admin\StaffController::class, 'restore'])->name('staff.restore');
        Route::resource('staff', App\Http\Controllers\Admin\StaffController::class);
        Route::match(['patch', 'post'], '/users/{user}/status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.status');
        Route::post('/users/{id}/restore', [App\Http\Controllers\Admin\UserController::class, 'restore'])->name('users.restore');
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::resource('pets', App\Http\Controllers\Admin\PetController::class);
        Route::delete('/pets/image/{image}', [App\Http\Controllers\Admin\PetController::class, 'deleteImage'])->name('pets.deleteImage');

        Route::patch('/franchises/{franchise}/status', [App\Http\Controllers\Admin\FranchiseController::class, 'toggleStatus'])->name('franchises.status');
        Route::post('/franchises/{id}/restore', [App\Http\Controllers\Admin\FranchiseController::class, 'restore'])->name('franchises.restore');
        Route::resource('franchises', App\Http\Controllers\Admin\FranchiseController::class);
        Route::post('/zones/{id}/restore', [App\Http\Controllers\Admin\ZoneController::class, 'restore'])->name('zones.restore');
        Route::resource('zones', App\Http\Controllers\Admin\ZoneController::class);

        Route::get('/categories/breeds/{category}', [App\Http\Controllers\Admin\PetController::class, 'getBreeds'])->name('categories.breeds');
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::put('/categories/{category}/standards', [App\Http\Controllers\Admin\CategoryController::class, 'updateStandards'])->name('categories.standards.update');

        Route::resource('category-types', App\Http\Controllers\Admin\CategoryTypeController::class);
        Route::resource('sizes', App\Http\Controllers\Admin\SizeController::class);
        Route::get('/categories/{category}/services', [App\Http\Controllers\Admin\CategoryController::class, 'getServices'])->name('categories.services');
        Route::get('/categories/{category}/details', [App\Http\Controllers\Admin\CategoryController::class, 'getDetails'])->name('categories.details');
        Route::patch('/services/{service}/status', [App\Http\Controllers\Admin\ServiceController::class, 'toggleStatus'])->name('services.status');
        Route::resource('services', App\Http\Controllers\Admin\ServiceController::class);
        Route::get('/services/{service}/details', [App\Http\Controllers\Admin\ServiceController::class, 'getDetails'])->name('services.details');
        Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class);
        Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class);
        Route::get('/weekly-schedules/franchise/{franchise}/edit', [App\Http\Controllers\Admin\WeeklyScheduleController::class, 'editWeek'])
            ->name('weekly-schedules.editWeek');
        Route::delete('/weekly-schedules/franchise/{franchise}', [App\Http\Controllers\Admin\WeeklyScheduleController::class, 'destroyWeek'])
            ->name('weekly-schedules.destroyWeek');
        Route::resource('weekly-schedules', App\Http\Controllers\Admin\WeeklyScheduleController::class);
        Route::patch('/banners/{banner}/status', [App\Http\Controllers\Admin\BannerController::class, 'toggleStatus'])->name('banners.status');
        Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);
        Route::resource('galleries', App\Http\Controllers\Admin\GalleryController::class);
        Route::get('cms-pages', [App\Http\Controllers\Admin\CmsPageController::class, 'index'])->name('cms-pages.index');
        Route::get('cms-pages/{key}/edit', [App\Http\Controllers\Admin\CmsPageController::class, 'edit'])->name('cms-pages.edit');
        Route::put('cms-pages/{key}', [App\Http\Controllers\Admin\CmsPageController::class, 'update'])->name('cms-pages.update');

        // Tax Settings
        Route::get('/tax', [App\Http\Controllers\Admin\TaxController::class, 'index'])->name('tax.index');
        Route::post('/tax', [App\Http\Controllers\Admin\TaxController::class, 'store'])->name('tax.store');

        Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])
            ->name('notifications.index');

        Route::resource('broadcast-notifications', App\Http\Controllers\Admin\BroadcastNotificationController::class)
            ->only(['index', 'create', 'store', 'show']);

        // Newsletter
        Route::get('/newsletters/create', [App\Http\Controllers\Admin\NewsletterController::class, 'create'])->name('newsletters.create');
        Route::post('/newsletters/send', [App\Http\Controllers\Admin\NewsletterController::class, 'send'])->name('newsletters.send');
        Route::resource('newsletters', App\Http\Controllers\Admin\NewsletterController::class)->only(['index', 'destroy']);
        Route::patch('/newsletters/{newsletter}/status', [App\Http\Controllers\Admin\NewsletterController::class, 'updateStatus'])->name('newsletters.status');

        // Contact Enquiries
        Route::resource('contacts', App\Http\Controllers\Admin\ContactController::class)->only(['index', 'show', 'destroy']);

        // Reports
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');

        // Chat Routes
        Route::get('/chat/messages', [App\Http\Controllers\Admin\ChatController::class, 'getMessages'])->name('chat.getMessages');
        Route::post('/chat/send', [App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('chat.sendMessage');
        Route::get('/bookings/{booking}/chat', [App\Http\Controllers\Admin\ChatPageController::class, 'show'])->name('bookings.chat');
    });
});
