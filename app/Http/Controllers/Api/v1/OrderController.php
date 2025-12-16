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
     * Create order from cart (checkout).
     *
     * Authentication is required. If the user is not authenticated, a clear
     * 401 response is returned so the frontend can redirect to login.
     */
    public function checkout(CheckoutRequest $request)
    {
            $client = auth('api')->user();

        if (!$client) {
            return $this->responseApi(
                [
                    'login_required' => true,
                ],
                "Authentication required. Please login to continue checkout.",
                false
            )->setStatusCode(401);
        }

        try {
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

        // Normalize to enum
        $statusEnum = $order->status instanceof OrderStatusEnum
            ? $order->status
            : OrderStatusEnum::from((int) $order->status);

        // Already cancelled
        if ($statusEnum === OrderStatusEnum::cancelled) {
            return $this->apiErrorMessage(
                "Order is already cancelled",
                400
            );
        }

        // Block cancellation once confirmed or later
        if ($statusEnum->value >= OrderStatusEnum::confirmed->value) {
            return $this->apiErrorMessage(
                "Order cannot be cancelled once it has been confirmed by admin. Current status: " . $statusEnum->label(),
                400
            );
        }

        // Allow cancellation
        $order->status = OrderStatusEnum::cancelled;
        $order->save();

        return $this->responseApi([], "Order cancelled successfully", 200);
    } catch (\Exception $e) {
        return $this->apiErrorMessage($e->getMessage(), 400);
    }
}
}

