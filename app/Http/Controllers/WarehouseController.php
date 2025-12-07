<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Item;
use App\Enums\WarehouseStatusEnum;
use App\Http\Requests\admin\WarehouseRequest;
use App\Services\StockManageService;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::withCount('items')->paginate(10);
        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouseStatus = WarehouseStatusEnum::labels();
        return view('admin.warehouses.create', compact('warehouseStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseRequest $request)
    {
        Warehouse::create($request->validated());
        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $warehouse = Warehouse::with('items')->findOrFail($id);
        return view('admin.warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $warehouse = Warehouse::with('items.unit', 'items.category')->findOrFail($id);
        $warehouseStatus = WarehouseStatusEnum::labels();
        return view('admin.warehouses.edit', compact('warehouse', 'warehouseStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseRequest $request, string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Update warehouse basic information
            $warehouse->update($request->validated());
            
            // Update item quantities if provided
            if ($request->has('item_quantities')) {
                $stockService = new StockManageService();
                $itemQuantities = $request->input('item_quantities', []);
                
                foreach ($itemQuantities as $itemId => $newQuantity) {
                    $item = Item::find($itemId);
                    if ($item) {
                        $newQuantity = floatval($newQuantity);
                        $stockService->adjustStock(
                            $item, 
                            $warehouse->id, 
                            $newQuantity,
                            'Quantity adjusted from warehouse edit page'
                        );
                    }
                }
            }
            
            DB::commit();
            return redirect()->route('admin.warehouses.index')
                ->with('success', 'Warehouse updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update warehouse: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();
        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }
}

