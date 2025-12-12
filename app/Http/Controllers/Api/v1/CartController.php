<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CartResource;
use App\Http\Requests\Api\V1\AddCartItemRequest;
use App\Http\Requests\Api\V1\UpdateCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Item;
use App\Enums\ItemStatusEnum;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use ApiResponse;

    /**
     * Get current user's cart with items
     */
    public function index()
    {
        $client = auth('api')->user();
        $cart = Cart::with('items.item.category', 'items.item.unit', 'items.item.mainPhoto')
            ->where('client_id', $client->id)
            ->first();

        if (!$cart) {
            // Create cart if it doesn't exist
            $cart = Cart::create(['client_id' => $client->id]);
            $cart->load('items.item.category', 'items.item.unit', 'items.item.mainPhoto');
        }

        return $this->responseApi(new CartResource($cart), "Cart retrieved successfully");
    }

    /**
     * Add item to cart
     */
    public function addItem(AddCartItemRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $client = auth('api')->user();
            
            // Get or create cart
            $cart = Cart::firstOrCreate(['client_id' => $client->id]);
            
            // Get item
            $item = Item::where('id', $request->item_id)
                ->where('status', ItemStatusEnum::active->value)
                ->firstOrFail();

            // Check if item already exists in cart
            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('item_id', $item->id)
                ->first();

            if ($existingCartItem) {
                // Update quantity
                $existingCartItem->quantity += $request->quantity;
                $existingCartItem->total_price = $existingCartItem->quantity * $existingCartItem->unit_price;
                $existingCartItem->save();
            } else {
                // Create new cart item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'item_id' => $item->id,
                    'quantity' => $request->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->price * $request->quantity,
                ]);
            }

            $cart->load('items.item.category', 'items.item.unit', 'items.item.mainPhoto');
            return $this->responseApi(new CartResource($cart), "Item added to cart successfully");
        });
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(UpdateCartItemRequest $request, $itemId)
    {
        return DB::transaction(function () use ($request, $itemId) {
            $client = auth('api')->user();
            $cart = Cart::where('client_id', $client->id)->firstOrFail();
            
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('item_id', $itemId)
                ->firstOrFail();

            $cartItem->quantity = $request->quantity;
            $cartItem->total_price = $cartItem->unit_price * $request->quantity;
            $cartItem->save();

            $cart->load('items.item.category', 'items.item.unit', 'items.item.mainPhoto');
            return $this->responseApi(new CartResource($cart), "Cart item updated successfully");
        });
    }

    /**
     * Remove item from cart
     */
    public function removeItem($itemId)
    {
        return DB::transaction(function () use ($itemId) {
            $client = auth('api')->user();
            $cart = Cart::where('client_id', $client->id)->firstOrFail();
            
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('item_id', $itemId)
                ->firstOrFail();

            $cartItem->delete();

            $cart->load('items.item.category', 'items.item.unit', 'items.item.mainPhoto');
            return $this->responseApi(new CartResource($cart), "Item removed from cart successfully");
        });
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        return DB::transaction(function () {
            $client = auth('api')->user();
            $cart = Cart::where('client_id', $client->id)->firstOrFail();
            
            $cart->items()->delete();

            $cart->load('items.item.category', 'items.item.unit', 'items.item.mainPhoto');
            return $this->responseApi(new CartResource($cart), "Cart cleared successfully");
        });
    }

    /**
     * Get cart total amount
     */
    public function getTotal()
    {
        $client = auth('api')->user();
        $cart = Cart::where('client_id', $client->id)->first();

        if (!$cart) {
            return $this->responseApi([
                'total_amount' => 0,
                'item_count' => 0
            ], "Cart total retrieved successfully");
        }

        $totalAmount = $cart->items->sum('total_price');
        $itemCount = $cart->items->count();

        return $this->responseApi([
            'total_amount' => $totalAmount,
            'item_count' => $itemCount
        ], "Cart total retrieved successfully");
    }
}

