<?php

namespace App\Services;

use App\Enums\WarehouseTransactionTypeEnum;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse;

class StockManageService
{
    public function initStock($item, $warehouseId, $initialStock)
    {
        $item->warehouses()->attach($warehouseId, ['quantity' => $initialStock]);
        $item->warehouseTransactions()->create([
            'transaction_type' => WarehouseTransactionTypeEnum::init,
            'quantity' => $initialStock,
            'quantity_after' => $initialStock,
            'description' => 'Initial Stock added to warehouse ID: ' . $warehouseId,
        ]);
    }

    public function decreaseStock($item, $warehouseId, $quantity,  $reference = null)
    {
        $stock = $item->warehouses()->where('itemable_id', $warehouseId)->first();
        if(!$stock){
            $this->initStock($item, $warehouseId, 0);
        }
        $item->warehouses()->where('itemable_id', $warehouseId)->decrement('quantity', $quantity);

        $item->warehouseTransactions()->create([
            'transaction_type' => WarehouseTransactionTypeEnum::sub,
            'quantity' => $quantity * -1,
            'quantity_after' => $item->warehouses()->where('itemable_id', $warehouseId)->first()->pivot->quantity,
            'description' => 'Stock decreased from warehouse ID: ' . $warehouseId . ($reference ? ', reference ID: ' . $reference->id : ''),
        ]);
    }

    public function increaseStock($item, $warehouseId, $quantity, $reference = null)
    {
        $stock = $item->warehouses()->where('itemable_id', $warehouseId)->first();
        if (!$stock) {
            $this->initStock($item, $warehouseId, 0);
        }
        $item->warehouses()->where('itemable_id', $warehouseId)->increment('quantity', $quantity);
        $item->warehouseTransactions()->create([
            'transaction_type' => WarehouseTransactionTypeEnum::add,
            'quantity' => $quantity,
            'quantity_after' => $item->warehouses()->where('itemable_id', $warehouseId)->first()->pivot->quantity,
            'description' => 'Stock increased at warehouse ID: ' . $warehouseId . ($reference ? ', Reference ID: ' . $reference->id : ''),
        ]);
    }

    public function adjustStock($item, $warehouseId, $newQuantity, $description = null)
    {
        $stock = $item->warehouses()->where('itemable_id', $warehouseId)->first();
        $oldQuantity = $stock ? $stock->pivot->quantity : 0;
        
        if (!$stock) {
            $item->warehouses()->attach($warehouseId, ['quantity' => $newQuantity]);
        } else {
            // Get the itemable_type directly from database
            $pivotRecord = DB::table('itemables')
                ->where('item_id', $item->id)
                ->where('itemable_id', $warehouseId)
                ->first();
            
            if ($pivotRecord) {
                // Update using the exact itemable_type from database
                DB::table('itemables')
                    ->where('item_id', $item->id)
                    ->where('itemable_id', $warehouseId)
                    ->where('itemable_type', $pivotRecord->itemable_type)
                    ->update(['quantity' => $newQuantity]);
            } else {
                // Fallback: update without itemable_type (shouldn't happen but just in case)
                DB::table('itemables')
                    ->where('item_id', $item->id)
                    ->where('itemable_id', $warehouseId)
                    ->update(['quantity' => $newQuantity]);
            }
        }
        
        $quantityDifference = $newQuantity - $oldQuantity;
        $item->warehouseTransactions()->create([
            'transaction_type' => WarehouseTransactionTypeEnum::adjust,
            'quantity' => $quantityDifference,
            'quantity_after' => $newQuantity,
            'description' => $description ?? 'Stock adjusted manually for warehouse ID: ' . $warehouseId . ' (from ' . $oldQuantity . ' to ' . $newQuantity . ')',
        ]);
    }
}
