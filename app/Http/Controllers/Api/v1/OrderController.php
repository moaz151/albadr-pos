<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderResource;
use App\Http\Requests\Api\V1\CheckoutRequest;
use App\Models\Order;
use App\Enums\OrderStatusEnum;
use App\Services\OrderService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiResponse;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Create order from cart (checkout)
     */
    public function checkout(CheckoutRequest $request)
    {
        try {
            $client = auth('api')->user();
            $order = $this->orderService->createOrderFromCart($client, $request);
            
            return $this->responseApi(
                new OrderResource($order->load('items')),
                "Order created successfully",
                true
            )->setStatusCode(201);
        } catch (\Exception $e) {
            return $this->apiErrorMessage($e->getMessage(), 400);
        }
    }

    /**
     * Get user's orders list
     */
    public function index(Request $request)
    {
        $client = auth('api')->user();
        
        $query = Order::where('client_id', $client->id)
            ->with('items')
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $perPage = min($request->get('per_page', 15), 100);
        $orders = $query->paginate($perPage);

        return $this->responseApi([
            'orders' => OrderResource::collection($orders),
            'pagination' => [
                'total' => $orders->total(),
                'count' => $orders->count(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'total_pages' => $orders->lastPage(),
            ]
        ], "Orders retrieved successfully");
    }

    /**
     * Get order details
     */
    public function show($id)
    {
        $client = auth('api')->user();
        
        $order = Order::with('items')
            ->where('client_id', $client->id)
            ->findOrFail($id);

        return $this->responseApi(
            new OrderResource($order),
            "Order retrieved successfully"
        );
    }

    /**
     * Cancel order (if status allows)
     */
    public function cancel($id)
{
    try {
        $client = auth('api')->user();
        
        $order = Order::where('client_id', $client->id)
            ->findOrFail($id);

        // Check if order is already cancelled
        if ($order->status == OrderStatusEnum::cancelled->value) {
            return $this->apiErrorMessage(
                "Order is already cancelled",
                400
            );
        }

        // Only allow cancellation if order status is BEFORE confirmed
        // Once admin confirms (status >= confirmed), client cannot cancel
        if ($order->status >= OrderStatusEnum::confirmed->value) {
            $currentStatus = OrderStatusEnum::from($order->status);
            return $this->apiErrorMessage(
                "Order cannot be cancelled once it has been confirmed by admin. Current status: " . $currentStatus->label(),
                400
            );
        }

        // Allow cancellation - set status to cancelled
        $order->status = OrderStatusEnum::cancelled->value;
        $order->save();

        return $this->responseApi([], "Order cancelled successfully", 200);
    } catch (\Exception $e) {
        return $this->apiErrorMessage($e->getMessage(), 400);
    }
}
}

