<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Item;
use App\Enums\ItemStatusEnum;
use Illuminate\Support\Facades\Log;

class CartSyncService
{
    /**
     * Sync local cart items into the authenticated client's online cart.
     *
     * @param  \App\Models\Client  $client
     * @param  array<int, array{item_id:int, quantity:float|int}>  $localCartItems
     * @return array{cart: \App\Models\Cart, warnings: array<int, string>, synced_items: int}
     */
    public function syncLocalCartToOnline($client, array $localCartItems): array
    {
        $warnings = [];
        $syncedItemsCount = 0;

        // Get or create the client's cart
        $cart = Cart::firstOrCreate(['client_id' => $client->id]);

        foreach ($localCartItems as $entry) {
            $itemId = $entry['item_id'] ?? null;
            $quantity = $entry['quantity'] ?? null;

            if (!$itemId || $quantity === null) {
                $warnings[] = "Skipped an item because item_id or quantity was missing.";
                continue;
            }

            // Validate item
            $item = Item::where('id', $itemId)
                ->where('status', ItemStatusEnum::active->value)
                ->first();

            if (!$item) {
                $message = "Local cart item {$itemId} is invalid or inactive. Skipped during sync.";
                $warnings[] = $message;
                Log::warning($message, ['client_id' => $client->id, 'item_id' => $itemId]);
                continue;
            }

            // Find existing cart item
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('item_id', $item->id)
                ->first();

            if ($cartItem) {
                // Merge quantity
                $cartItem->quantity += $quantity;
                $cartItem->unit_price = $item->price; // use current price
                $cartItem->total_price = $cartItem->quantity * $cartItem->unit_price;
                $cartItem->save();
            } else {
                // Create new cart item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->price * $quantity,
                ]);
            }

            $syncedItemsCount++;
        }

        $cart->load('items.item.category', 'items.item.unit', 'items.item.mainPhoto');

        return [
            'cart' => $cart,
            'warnings' => $warnings,
            'synced_items' => $syncedItemsCount,
        ];
    }
}



