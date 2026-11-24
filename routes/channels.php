<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Booking;
use App\Models\Admin;
use App\Models\User;
use App\Models\Staff;

// Explicitly register broadcast routes with multiple guards including API
Broadcast::routes(['middleware' => ['web', 'auth:api,admin,staff,web']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['web', 'api']]);

Broadcast::channel('bookings.{id}', function ($user, $id) {
    // 1. Admins have access to all chat channels
    if ($user instanceof Admin) {
        return true;
    }

    $booking = Booking::find($id);
    if (!$booking) {
        return false;
    }

    // 2. The user who owns the booking can listen
    if ($user instanceof User && (int) $user->id === (int) $booking->user_id) {
        return true;
    }

    // 3. The staff assigned to the booking can listen
    if ($user instanceof Staff && (int) $user->id === (int) $booking->staff_id) {
        return true;
    }

    return false;
}, ['guards' => ['web', 'admin', 'staff', 'api']]);

Broadcast::channel('admin.notifications', function ($user) {
    return $user instanceof Admin;
}, ['guards' => ['admin', 'api']]);
