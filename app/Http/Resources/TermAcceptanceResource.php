<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TermAcceptanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'accepted' => (bool) $this->accepted,
            'image' => $this->image,
            'cms_page' => $this->whenLoaded('cmsPage', function () {
                return [
                    'id' => $this->cmsPage->id,
                    'page_key' => $this->cmsPage->page_key,
                    'title' => $this->cmsPage->title,
                ];
            }),
            'accepted_at' => $this->created_at?->format('d F Y h:i A'),
        ];
    }
}
