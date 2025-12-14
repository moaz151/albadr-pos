<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'status' => [
                'value' => $this->status,
            ],
            'payment_method' => 'cash',
            'price' => $this->price,
            'shipping_cost' => $this->shipping_cost,
            'total_price' => $this->total_price,
            'shipping_name' => $this->shipping_name,
            'shipping_address' => $this->shipping_address,
            'shipping_phone' => $this->shipping_phone,
            'notes' => $this->notes,
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'item_code' => $item->item_code,
                    'quantity' => $item->pivot->quantity,
                    'unit_price' => $item->pivot->unit_price,
                    'total_price' => $item->pivot->total_price,
                ];
            }),
            'sale_id' => $this->sale_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

