<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CartResource;
use App\Http\Requests\Api\V1\AddCartItemRequest;
use App\Http\Requests\Api\V1\UpdateCartItemRequest;
use App\Http\Requests\Api\V1\SyncCartRequest;
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
     * Retrieve the guest cart from session storage.
     *
     * @return array{items: array<int, array<string, mixed>>}
     */
    protected function getGuestCart(): array
    {
        $cart = session()->get('guest_cart', null);

        if (!is_array($cart) || !isset($cart['items']) || !is_array($cart['items'])) {
            $cart = [
                'items' => [],
            ];
        }

        return $cart;
    }

    /**
     * Persist the guest cart into session storage.
     *
     * @param  array{items: array<int, array<string, mixed>>}  $cart
     * @return void
     */
    protected function saveGuestCart(array $cart): void
    {
        session(['guest_cart' => $cart]);
    }

    /**
     * Build a standard response payload for guest carts.
     */
    protected function buildGuestCartResponse(array $cart, string $message)
    {
        $items = $cart['items'] ?? [];

        $totalAmount = 0;
        foreach ($items as $item) {
            $totalAmount += $item['total_price'];
        }

        $itemCount = count($items);

        return $this->responseApi([
            'items' => $items,
            'total_amount' => $totalAmount,
            'item_count' => $itemCount,
            'is_guest' => true,
        ], $message);
    }

    /**
     * Get current user's cart with items.
     *
     * For unauthenticated users, returns an empty cart structure so the
     * frontend can rely on a consistent response shape while using
     * localStorage for actual guest cart data.
     */
    public function index()
    {
        $client = auth('api')->user();

        if (!$client) {
            $cart = $this->getGuestCart();
            return $this->buildGuestCartResponse($cart, "Guest cart retrieved successfully from session.");
        }

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
     * Add item to cart.
     *
     * For unauthenticated users, this endpoint stores the cart "locally"
     * in the session instead of the database.
     */
    public function addItem(AddCartItemRequest $request)
    {
        $client = auth('api')->user();

        if (!$client) {
            // Guest cart stored in session
            $cart = $this->getGuestCart();

            // Get item and ensure it is active, similar to authenticated flow
            $item = Item::where('id', $request->item_id)
                ->where('status', ItemStatusEnum::active->value)
                ->first();

            if (!$item) {
                return $this->apiErrorMessage("This item is not available.", 400);
            }

            $items = $cart['items'];
            $foundIndex = null;

            foreach ($items as $index => $cartItem) {
                if (($cartItem['item_id'] ?? null) === $item->id) {
                    $foundIndex = $index;
                    break;
                }
            }

            if ($foundIndex !== null) {
                // Merge quantity with existing entry
                $items[$foundIndex]['quantity'] += $request->quantity;
                $items[$foundIndex]['unit_price'] = $item->price;
                $items[$foundIndex]['total_price'] = $items[$foundIndex]['quantity'] * $items[$foundIndex]['unit_price'];
            } else {
                // Add new guest cart entry
                $items[] = [
                    'item_id' => $item->id,
                    'name' => $item->name,
                    'unit_price' => $item->price,
                    'quantity' => $request->quantity,
                    'total_price' => $item->price * $request->quantity,
                ];
            }

            $cart['items'] = $items;
            $this->saveGuestCart($cart);

            return $this->buildGuestCartResponse($cart, "Item added to guest cart (session) successfully.");
        }

        return DB::transaction(function () use ($request, $client) {
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
                $existingCartItem->unit_price = $item->price;
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
     * Update cart item quantity.
     *
     * For unauthenticated users, this updates the item in the session-based
     * guest cart instead of the database.
     */
    public function updateItem(UpdateCartItemRequest $request, $itemId)
    {
        $client = auth('api')->user();

        if (!$client) {
            $cart = $this->getGuestCart();
            $items = $cart['items'];
            $updated = false;

            foreach ($items as $index => $cartItem) {
                if (($cartItem['item_id'] ?? null) === (int) $itemId) {
                    $items[$index]['quantity'] = $request->quantity;
                    $items[$index]['total_price'] = $items[$index]['unit_price'] * $request->quantity;
                    $updated = true;
                    break;
                }
            }

            if (!$updated) {
                return $this->apiErrorMessage("Item not found in guest cart.", 404);
            }

            $cart['items'] = $items;
            $this->saveGuestCart($cart);

            return $this->buildGuestCartResponse($cart, "Guest cart item updated successfully.");
        }

        return DB::transaction(function () use ($request, $itemId, $client) {
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
     * Remove item from cart.
     *
     * For unauthenticated users, this removes the item from the
     * session-based guest cart.
     */
    public function removeItem($itemId)
    {
        $client = auth('api')->user();

        if (!$client) {
            $cart = $this->getGuestCart();
            $items = $cart['items'];

            $items = array_values(array_filter($items, function ($cartItem) use ($itemId) {
                return ($cartItem['item_id'] ?? null) !== (int) $itemId;
            }));

            $cart['items'] = $items;
            $this->saveGuestCart($cart);

            return $this->buildGuestCartResponse($cart, "Item removed from guest cart successfully.");
        }

        return DB::transaction(function () use ($itemId, $client) {
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
     * Clear entire cart.
     *
     * For unauthenticated users, this clears the session-based guest cart.
     */
    public function clear()
    {
        $client = auth('api')->user();

        if (!$client) {
            session()->forget('guest_cart');
            $cart = ['items' => []];

            return $this->buildGuestCartResponse($cart, "Cleared guest cart successfully.");
        }

        return DB::transaction(function () use ($client) {
            $cart = Cart::where('client_id', $client->id)->firstOrFail();
            
            $cart->items()->delete();

            $cart->load('items.item.category', 'items.item.unit', 'items.item.mainPhoto');
            return $this->responseApi(new CartResource($cart), "Cart cleared successfully");
        });
    }

    /**
     * Get cart total amount.
     *
     * For unauthenticated users, returns totals based on the
     * session-based guest cart.
     */
    public function getTotal()
    {
        $client = auth('api')->user();

        if (!$client) {
            $cart = $this->getGuestCart();
            return $this->buildGuestCartResponse($cart, "Guest cart total retrieved successfully.");
        }

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

    /**
     * Sync local (device) cart to online cart for authenticated users.
     */
    public function syncCart(SyncCartRequest $request)
    {
        $client = auth('api')->user();

        if (!$client) {
            return $this->apiErrorMessage("Authentication required to sync cart.", 401);
        }

        $cartSyncService = app(\App\Services\CartSyncService::class);

        $result = $cartSyncService->syncLocalCartToOnline($client, $request->items);

        return $this->responseApi([
            'cart' => new CartResource($result['cart']),
            'warnings' => $result['warnings'],
            'synced_items' => $result['synced_items'],
        ], "Cart synced successfully");
    }
}

