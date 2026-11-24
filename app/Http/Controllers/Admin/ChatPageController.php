<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\User;
use App\Models\Chat;

class ChatPageController extends Controller
{
    public function show(Booking $booking)
    {
        $user = $booking->user;

        if (!$user) {
            return redirect()->back()->with('error', 'Customer not found for this booking.');
        }

        // Mark messages as read
        Chat::where('booking_id', $booking->id)
            ->where('receiver_id', auth()->guard('admin')->id())
            ->where('receiver_type', \App\Models\Admin::class)
            ->update(['read' => 1]);

        return view('admin.chat.show', compact('booking', 'user'));
    }
}
