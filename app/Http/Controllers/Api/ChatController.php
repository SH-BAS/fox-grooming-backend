<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ChatResource;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index(Request $request, $bookingId)
    {
        // ... (omitting index for brevity but keeping it unchanged)
        $booking = Booking::find($bookingId);
        if (!$booking) {
            return $this->error('Booking not found', 404);
        }
        if ($booking->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }
        $userId = $request->user()->id;
        $staffId = $booking->staff_id;

        $messages = $booking->chats()
            ->where(function ($q) use ($userId, $staffId) {

                // User → Staff
                $q->where('sender_id', $userId)
                    ->where('sender_type', 'App\Models\User')
                    ->where('receiver_id', 1)
                    ->where('receiver_type', 'App\Models\Admin');

            })
            ->orWhere(function ($q) use ($userId, $staffId) {

                // Staff → User
                $q->where('sender_id', 1)
                    ->where('sender_type', 'App\Models\Admin')
                    ->where('receiver_id', $userId)
                    ->where('receiver_type', 'App\Models\User');

            })
            ->orderBy('created_at', 'asc')
            ->get();
        if ($messages->isEmpty()) {

            return $this->success([],'No messages found', 200);
        }
        return $this->success(ChatResource::collection($messages), 'Chat messages retrieved');
    }

    public function store(Request $request, $bookingId)
    {
        $booking = Booking::find($bookingId);
        if (!$booking) {
            return $this->error('Booking not found', 404);
        }
        if ($booking->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'type' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        try {
            $user = $request->user();
            $receiverId = $booking->staff_id;
            $receiverType = $booking->staff_id ? 'App\Models\Staff' : 'App\Models\Admin';
            if (!$receiverId) {
                $receiverId = 1; // Default to first admin
            }

            $chat = Chat::create([
                'booking_id' => $booking->id,
                'sender_id' => $user->id,
                'sender_type' => get_class($user),
                'receiver_id' => 1,
                'receiver_type' => 'App\Models\Admin',
                'message' => $request->message,
                'type' => 'booking',
                'read' => false
            ]);

            broadcast(new MessageSent($chat))->toOthers();

            return $this->success($chat, 'Message sent successfully', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
