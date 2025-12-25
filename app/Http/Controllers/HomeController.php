<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get items with low stock (total stock <= minimum_stock)
        $lowStockItems = Item::whereNotNull('minimum_stock')
            ->where('status', \App\Enums\ItemStatusEnum::active)
            ->with('warehouses')
            ->get()
            ->map(function ($item) {
                // Calculate total stock across all warehouses using the relationship
                // This ensures we only count warehouse relationships, not sales
                $totalStock = $item->warehouses->sum(function ($warehouse) {
                    return $warehouse->pivot->quantity ?? 0;
                });
                
                return [
                    'item' => $item,
                    'total_stock' => $totalStock,
                    'minimum_stock' => $item->minimum_stock,
                ];
            })
            ->filter(function ($data) {
                // Filter items where total stock is less than or equal to minimum stock
                return $data['total_stock'] <= $data['minimum_stock'];
            })
            ->values();

        return view('admin.home', compact('lowStockItems'));
    }
}
