<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        // Opening the notifications page marks all as read (bell count clears)
        Notification::query()
            ->where('admin_id', $admin->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $notifications = Notification::query()
            ->where('admin_id', $admin->id)
            ->with(['booking.user'])
            ->latest('id')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }
}
