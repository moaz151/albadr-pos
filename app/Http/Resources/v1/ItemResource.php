<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'name' => $this->name,
            'item_code' => $this->item_code,
            'price' => $this->price,
            'description' => $this->description,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? ['id' => $this->category->id, 'name' => $this->category->name] : null;
            }),
            'unit' => $this->whenLoaded('unit', function () {
                return $this->unit ? ['id' => $this->unit->id, 'name' => $this->unit->name] : null;
            }),
            'images' => $this->whenLoaded('gallery', function () {
                return $this->gallery->map(function ($image) {
                    return $image->url ?? null;
                })->filter()->values();
            }, []),
            'main_photo' => $this->whenLoaded('mainPhoto', function () {
                return $this->mainPhoto ? $this->mainPhoto->url : null;
            }),
            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
            ],
        ];
    }
}
