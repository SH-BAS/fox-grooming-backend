<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\AuthController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\ProfileController;
use App\Http\Controllers\Staff\BookingController;

// Staff Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Protected Staff Routes
Route::middleware(['auth:staff'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password/update', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Booking Routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::post('/bookings/{booking}/add-service', [BookingController::class, 'addService'])->name('bookings.addService');
    Route::put('/bookings/services/{bookedService}', [BookingController::class, 'updateBookedService'])->name('bookings.updateService');
    Route::delete('/bookings/services/{bookedService}', [BookingController::class, 'deleteBookedService'])->name('bookings.deleteService');
    Route::get('/bookings/{booking}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
    Route::post('/bookings/{booking}/upload-document', [BookingController::class, 'uploadDocument'])->name('bookings.uploadDocument');
    Route::get('/categories/{category}/services', [BookingController::class, 'getCategoryServices'])->name('bookings.categoryServices');
    Route::get('/services/{service}/details', [BookingController::class, 'getServiceDetails'])->name('bookings.serviceDetails');

    // Schedule Routes
    Route::get('/bookings/schedule/view', [BookingController::class, 'schedule'])->name('bookings.schedule');
    Route::get('/bookings/schedule/data', [BookingController::class, 'scheduleData'])->name('bookings.scheduleData');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
