<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'minutes' => $this->minutes,
            'pricing_type' => $this->pricing_type,
            'price' => $this->pricing_type == 'fixable' ? $this->sizes[0]->price : null,
            'image' => $this->image,
            'sizes' => $this->sizes->map(function ($size) {
                if ($this->pricing_type == 'sizeable') {
                    return [
                        'id' => $size->size->id,
                        'name' => $size->size->name,
                        'price' => $size->price
                    ];
                }
            }),
        ];
    }
}
