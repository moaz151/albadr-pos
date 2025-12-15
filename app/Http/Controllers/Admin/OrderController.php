<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Enums\OrderStatusEnum;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * List all orders with filters
     */
    public function index(Request $request)
    {
        $orders = Order::with(['client', 'items', 'sale'])
        ->latest()
        ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * View order details
     */
    public function show($id)
    {
        $order = Order::with(['client', 'items', 'sale'])->findOrFail($id);
        $orderStatuses = OrderStatusEnum::labels();
        
        return view('admin.orders.show', compact('order', 'orderStatuses'));
    }

    /**
     * Update order status
     */
    public function updateStatus(UpdateOrderStatusRequest $request, $id)
    {
        $this->orderService->updateOrderStatus(Order::findOrFail($id), OrderStatusEnum::from($request->status));
        return redirect()->route('admin.orders.show', $id)->with('success', 'Order status updated successfully');
    }

    /**
     * Manually convert order to sale (if needed)
     */
    // public function convertToSale($id)
    // {
    //     $order = Order::findOrFail($id);
        
    //     try {
    //         if ($order->sale_id) {
    //             return redirect()
    //                 ->back()
    //                 ->with('error', 'Order already has a sale record');
    //         }

    //         $sale = $this->orderService->convertOrderToSale($order);
    //         // from AI
    //         // $this->orderService->convertOrderToSale($order);
            
    //         return redirect()
    //             ->route('admin.orders.show', $id)
    //             ->with('success', 'Order converted to sale successfully');
    //     } catch (\Exception $e) {
    //         return redirect()
    //             ->back()
    //             ->with('error', 'Error converting order to sale: ' . $e->getMessage());
    //     }
    // }
}

