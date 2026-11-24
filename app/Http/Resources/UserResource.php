<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array

    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->image,
            'role' => $this->role ? $this->role->name : null,
            'verification_token' => $this->verification_token,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'access_token' => $this->access_token,
        ];
    }
}
