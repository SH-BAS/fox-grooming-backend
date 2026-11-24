<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pet' => new PetResource($this->pet),
            'staff' => $this->staff ? $this->staff->name : 'Unassigned',
            'booking_date' => Carbon::parse($this->booking_date)->format('d F Y'),
            'booking_time' => Carbon::parse($this->booking_time)->format('h:i A'),
            'status' => $this->status,
            'base_price' => (float) $this->base_price,
            'tax' => (float) $this->tax,
            'total_price' => (float) $this->total_price,
            'user_notes' => $this->user_notes,
            'document' => $this->document ? asset('storage/' . $this->document) : null,
            'address' => $this->address,
            'zip_code' => $this->zip_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_reviewed' => (bool) $this->is_reviewed,
            'services' => $this->bookedServices->map(function ($bs) {
                   $size = $bs->service->pricing_type == 'fixable'
                    ? $bs->service->sizes->whereNull('size_id')->first()
                    : $bs->service->sizes->where('size_id', $this->pet->size_id)->first();

                return [
                    'id' => $bs->id,
                    'service_name' => $bs->service ? $bs->service->name : 'N/A',
                    'price' => (float) ($size->price ?? 0),
                    'quantity' => (int) $bs->quantity,
                ];
            }),
            'review' => $this->review,
            'term_acceptance' => $this->whenLoaded('termAcceptance', fn () => new TermAcceptanceResource($this->termAcceptance)),
            'logs' => $this->logs,
            'chats' => $this->chats,
            'created_at' => $this->created_at->format('d F Y'),
        ];
    }
}
