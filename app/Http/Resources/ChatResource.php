<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Admin;
use App\Models\User;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'is_admin' => $this->receiver_type === Admin::class,
            'is_user' => $this->sender_type === User::class,
            'message' => $this->message,
            'type' => $this->type,
            'read' => $this->read,
            'date' => $this->created_at->format('d F Y'),
            'time' => $this->created_at->format('h:i A'),
        ];
    }
}
