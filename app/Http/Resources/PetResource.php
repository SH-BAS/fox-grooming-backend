<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class PetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profile_image' => $this->profile_image,
            'category_id' => $this->category_id,
            'category' => $this->category ? $this->category->name : null,
            'category_type' => $this->type ? $this->type->name : null,
            'size' => $this->size ? $this->size->name : null,
            'size_id' => $this->size_id,
            'age' => $this->age,
            'weight' => $this->weight,
            'gender' => $this->gender,
            'is_vaccinated' => (bool)$this->is_vaccinated,
            'notes' => $this->notes,
            'images' => $this->images->map(function($img) {
                return $img->image;
            }),
            'documents' => $this->documents->map(function($doc) {
                return [
                    'id' => $doc->id,
                    'url' => $doc->document,
                    'name' => basename($doc->document)
                ];
            }),
            'created_at' => Carbon::parse($this->created_at)->format('d F Y'),
        ];
    }
}
