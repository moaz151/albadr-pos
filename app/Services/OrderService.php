<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Sale;
use App\Models\Item;
use App\Models\Warehouse;
use App\Models\Safe;
use App\Models\User;
use App\Enums\OrderStatusEnum;
use App\Enums\SaleTypeEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\DiscountTypeEnum;
use App\Enums\SafeStatusEnum;
use App\Enums\ItemStatusEnum;
use App\Http\Requests\Api\V1\CheckoutRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Settings\GeneralSettings;

class OrderService
{
    /**
     * Create order from cart
     */
    public function createOrderFromCart($client, CheckoutRequest $request): Order
    {
        return DB::transaction(function () use ($client, $request) {
            // Get client's cart (if it exists)
            $cart = Cart::with('items.item')
                ->where('client_id', $client->id)
                ->first();

            // If no cart or no items, treat as empty cart
            if (!$cart || $cart->items->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            // Validate stock availability
            foreach ($cart->items as $cartItem) {
                $item = $cartItem->item;
                // Note: Stock validation would need warehouse context
                // For now, we'll proceed and handle stock when order is confirmed
            }

            // Generate unique order number
            $orderNumber = str_pad('ORD-', 6, '0', STR_PAD_LEFT) . '-' . time();

            // Calculate totals
            $price = $cart->items->sum('total_price');
            $settings = app(GeneralSettings::class);
            $shippingCost = $settings->shipping_cost;
            $totalPrice = $price + $shippingCost;

            // Create order
            $order = Order::create([
                'client_id' => $client->id,
                'order_number' => $orderNumber,
                'status' => OrderStatusEnum::pending->value,
                'payment_method' => PaymentTypeEnum::cash->value,
                'price' => $price,
                'shipping_cost' => $shippingCost,
                'total_price' => $totalPrice,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_phone' => $request->shipping_phone,
                'notes' => $request->notes,
            ]);

            // Attach items to order
            foreach ($cart->items as $cartItem) {
                $order->items()->attach($cartItem->item_id, [
                    'unit_price' => $cartItem->unit_price,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->total_price,
                ]);
            }

            // Clear cart after order creation
            $cart->items()->delete();

            // Note: Sale will be created automatically when order status changes to "Delivered"
            // This is handled in updateOrderStatus method

            return $order->load('items');
        });
    }

    /**
     * Convert order to Sale when delivered
     */
    public function convertOrderToSale(Order $order): Sale
    {
        return DB::transaction(function () use ($order) {
            // Check if sale already exists
            if ($order->sale_id) {
                return $order->sale;
            }

            // Get default warehouse (first active warehouse)
            $warehouse = Warehouse::where('status', \App\Enums\WarehouseStatusEnum::active->value)->first();
            if (!$warehouse) {
                throw new \Exception('No active warehouse found');
            }

            // Get default safe (first active safe)
            $safe = Safe::where('status', SafeStatusEnum::active->value)->first();
            if (!$safe) {
                throw new \Exception('No active safe found');
            }

            // Get default user (first admin user or system user)
            $user = User::first();
            if (!$user) {
                throw new \Exception('No user found');
            }

            // Generate invoice number
            $invoiceNumber = 'INV-' . strtoupper(Str::random(4)) . '-' . time();

            // Calculate totals
            $total = $order->price;
            $discount = 0; // No discount for orders initially
            $shippingCost = $order->shipping_cost;
            $netAmount = $total - $discount + $shippingCost;
            $paidAmount = 0;
            if ($order->payment_method === PaymentTypeEnum::cash->value) {
                // For cash on delivery we assume full payment collected at delivery
                $paidAmount = $netAmount;
            }

            // Create sale
            $sale = Sale::create([
                'client_id' => $order->client_id,
                'user_id' => $user->id,
                'safe_id' => $safe->id,
                'warehouse_id' => $warehouse->id,
                'type' => SaleTypeEnum::sale->value,
                'total' => $total,
                'discount' => $discount,
                'discount_type' => DiscountTypeEnum::fixed->value,
                'shipping_cost' => $shippingCost,
                'net_amount' => $netAmount,
                'invoice_number' => $invoiceNumber,
                'payment_type' => PaymentTypeEnum::cash->value,
            ]);

            // Attach items to sale
            foreach ($order->items as $orderItem) {
                $item = Item::findOrFail($orderItem->id);
                $sale->items()->attach($item->id, [
                    'unit_price' => $orderItem->pivot->unit_price,
                    'quantity' => $orderItem->pivot->quantity,
                    'total_price' => $orderItem->pivot->total_price,
                    'notes' => 'order Number: ' . $order->order_number,
                ]);

                // Decrease stock
                (new StockManageService())->decreaseStock($item, $warehouse->id, $orderItem->pivot->quantity, $sale);
            }

            // Update order with sale_id
            $order->sale_id = $sale->id;
            $order->save();

            // Handle safe transaction if payment received
            if ($paidAmount > 0) {
                (new SafeService())->inTransaction($sale, $paidAmount, 'Order Payment, Invoice #: ' . $invoiceNumber);
            }

            // Handle client account balance
            (new ClientAccountService())->handleClientBalance($sale, $netAmount, $paidAmount, $invoiceNumber);

            return $sale;
        });
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Order $order, OrderStatusEnum $status): Order
    {
        return DB::transaction(function () use ($order, $status) {
            $oldStatusValue = $order->status;
            $order->status = $status->value;
            
            $order->save();

            // If status changed to confirmed, convert to sale once
            if ($status === OrderStatusEnum::confirmed && $oldStatusValue !== OrderStatusEnum::confirmed->value) {
                if (!$order->sale_id) {
                    $this->convertOrderToSale($order);
                }
            }

            return $order->fresh();
        });
    }
}

