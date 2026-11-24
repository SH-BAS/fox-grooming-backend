<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Chat;
use App\Models\User;
use App\Models\Admin;
use App\Models\Booking;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function getMessages(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $messages = Chat::where('booking_id', $request->booking_id)
            ->where(function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('sender_id', $request->user_id)->where('sender_type', User::class);
                })->orWhere(function ($q2) use ($request) {
                    $q2->where('receiver_id', $request->user_id)->where('receiver_type', User::class);
                });
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_admin' => $msg->sender_type === Admin::class,
                    'time' => $msg->created_at->format('h:i A'),
                    'date' => $msg->created_at->format('M d'),
                ];
            });

        // Mark as read
        Chat::where('booking_id', $request->booking_id)
            ->where('receiver_id', auth()->guard('admin')->id())
            ->where('receiver_type', Admin::class)
            ->update(['read' => 1]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $chat = Chat::create([
            'sender_id' => auth()->guard('admin')->id(),
            'sender_type' => Admin::class,
            'receiver_id' => $request->user_id,
            'receiver_type' => User::class,
            'booking_id' => $request->booking_id,
            'type' => 'booking',
            'message' => $request->message,
            'read' => 0,
        ]);
        broadcast(new MessageSent($chat))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => [
                'id' => $chat->id,
                'message' => $chat->message,
                'is_admin' => true,
                'time' => $chat->created_at->format('h:i A'),
            ]
        ]);
    }
}
