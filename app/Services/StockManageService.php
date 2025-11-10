<?php

namespace App\Services;

use App\Enums\WarehouseTransactionTypeEnum;

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
}