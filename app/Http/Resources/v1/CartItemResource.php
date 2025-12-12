<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'item' => new ItemResource($this->whenLoaded('item')),
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
        ];
    }
}

