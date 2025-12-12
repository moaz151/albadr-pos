<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = $this->items;
        $totalAmount = $items->sum('total_price');
        $itemCount = $items->count();

        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'items' => CartItemResource::collection($items),
            'total_amount' => $totalAmount,
            'item_count' => $itemCount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
