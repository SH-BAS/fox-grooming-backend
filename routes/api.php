<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\ApiServiceController;
use App\Http\Controllers\Api\ApiGalleryController;
use App\Http\Controllers\Api\ApiCmsPageController;
use App\Http\Controllers\Api\BasePriceController;
use App\Http\Controllers\Api\ApiBannerController;
use App\Http\Controllers\Api\ApiFranchiseController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-token', [AuthController::class, 'verifyToken']);
Route::post('/resend-verification', [AuthController::class, 'resendVerificationToken']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// category & breeds
Route::get('/all-categories', [CategoryController::class, 'index']);
Route::get('/category-detail/{id}', [CategoryController::class, 'show']);
Route::get('/breed-list/{categoryId}', [CategoryController::class, 'breedList']);

//sizes
Route::get('/all-sizes', [SizeController::class, 'index']);

//service
Route::get('/all-services', [ApiServiceController::class, 'index']);
Route::get('category-wise-services/{categoryId}', [ApiServiceController::class, 'categoryWiseServices']);
Route::get('service-detail/{serviceId}', [ApiServiceController::class, 'serviceDetail']);

//gallery Content
Route::get('gallery', [ApiGalleryController::class, 'index']);
Route::get('category-wise-gallery/{categoryId}', [ApiGalleryController::class, 'categoryWise']);

//banner
Route::get('all-banners', [ApiBannerController::class, 'index']);
Route::get('category-wise-banners/{categoryId}', [ApiBannerController::class, 'categoryWise']);

//CMS Pages
Route::get('cms-pages', [ApiCmsPageController::class, 'index']);

// Category base price
Route::get('base-price/{petId}', [BasePriceController::class, 'basePrice']);
Route::get('time-availability', [BasePriceController::class, 'getTimeSlots']);

//franchise
Route::apiResource('franchise', ApiFranchiseController::class);

//reviews
Route::get('all-reviews', [ReviewController::class, 'index']);
Route::get('e-terms', [BookingController::class, 'getESignatureAgreement']);


// Protected Routes
Route::middleware('auth:api')->group(function () {

    // Broadcast auth route for API clients (creates /api/broadcasting/auth)
    Broadcast::routes();

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::get('/logout', [ProfileController::class, 'logout']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // Pets
    Route::apiResource('pets', PetController::class);
    Route::delete('pet-images/{petImage}', [PetController::class, 'removeImage']);
    Route::delete('pet-documents/{petDocument}', [PetController::class, 'removeDocument']);

    // Bookings
    Route::apiResource('bookings', BookingController::class);
    
    Route::get('order-history', [BookingController::class, 'orderHistory']);
    //cancel booking
    Route::get('bookings/{id}/cancel', [BookingController::class, 'cancel']);

    // Reviews
    Route::post('reviews', [ReviewController::class, 'store']);
    Route::get('reviews', [ReviewController::class, 'index']);
    Route::get('bookings/{booking}/review', [ReviewController::class, 'getBookingReview']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/{id}', [NotificationController::class, 'show']);

    // Chats
    Route::get('/bookings/{booking}/chats', [ChatController::class, 'index']);
    Route::post('/bookings/{booking}/chats', [ChatController::class, 'store']);

});
