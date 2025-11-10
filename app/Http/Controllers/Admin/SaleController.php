<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Safe;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Item;
use App\Models\Sale;
use App\Models\User;
use App\Models\Warehouse;
use App\Enums\SafeStatusEnum;
use App\Enums\UnitStatusEnum;
use App\Enums\CategoryStatusEnum;
use App\Enums\ClientStatusEnum;
use App\Enums\ItemStatusEnum;
use App\Enums\DiscountTypeEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\SafeTransactionTypeEnum;
use App\Http\Requests\Admin\SaleRequest;
use Illuminate\Support\Facades\DB;
use App\Services\SafeService;
use App\Services\StockManageService;
use App\Services\ClientAccountService;


class SaleController extends Controller
{
    public function create()
    {
        // @TODO: from settings
        $clients = Client::Where('status', ClientStatusEnum::active)->get();
        $safes = Safe::where('status', SafeStatusEnum::active)->get();
        $units = Unit::where('status', UnitStatusEnum::active)->get();
        $warehouses = Warehouse::all();
        $items = Item::where('status', ItemStatusEnum::active)->get();
        $discountTypes = DiscountTypeEnum::labels();
        return view('admin.sales.create',
         compact('clients', 'safes', 'units', 'items', 'discountTypes', 'warehouses'));
    }

    public function store(SaleRequest $request)
    {
        DB::beginTransaction();
        /** @var User $user */
        $user = Auth::user();
        $sale = $user->sales()->create($request->validated());
        $total = $this->attachItems($request, $sale);
        $this->updateSaleTotals($total, $request, $sale);
        (new SafeService())->inTransaction($sale, $sale->paid_amount, 'Sale Payment, Invoice #: ' . $sale->invoice_number);
        // $this->updateClientAccountBalance($sale);
        (new ClientAccountService())->handleClientBalance($sale, $sale->net_amount, $sale->paid_amount);
        // client account update
        DB::commit();
        return back()->with('success', __('trans.saved_successfully'));

    }


    /**
     * @param Sale $sale
     * 
     * @return void
     */
    // @TODO: moved to ClientAccountBalanceService
    private function updateClientAccountBalance(Sale $sale): void
    {
        $balance = $sale->net_amount - $sale->paid_amount;
        if($balance != 0){
            // client account update
            $sale->client->increment('balance', $balance);
        }

        $sale->clientAccountTransaction()->create([
            
            'user_id' => auth()->user()->id, 
            'client_id' => $sale->client_id,
            'credit' => $sale->net_amount,
            'debit' => $sale->paid_amount,
            'balance' => $balance,
            'balance_after' => $sale->client->fresh()->balance,
            'description' => __('trans.sale_remaining, Invoice Number: ' . $sale->invoice_number),
        ]);
    }

    

    /**
     * @param SaleRequest $request
     * @param Sale $sale
     * @return float
     */
    private function attachItems(SaleRequest $request, Sale $sale): float
    {
        $total = 0;

        foreach($request->items as $id => $item){
            $queriedItem = Item::find($id);
            $totalPrice = $queriedItem->price * $item['qty'];
            $sale->items()->attach([
                $id => [
                    'unit_price' => $item['price'],
                    'quantity' => $item['qty'],
                    'total_price' => $totalPrice,
                    'notes' => $item['notes'],
                ]
            ]);
            // Stock Update
            // $queriedItem->decrement('quantity', $item['qty']);
            (new StockManageService())->decreaseStock($queriedItem, $request->warehouse_id, $item['qty'], $sale);
            $total += $totalPrice;
        }
        return $total;
    }

    private function calculateDiscount(SaleRequest $request, float $total)
    {
        if($request->discount_type == DiscountTypeEnum::percentage->value){
            $discount = $total * ($request->discount_value/100);
        }else {
            $discount = $request->discount_value;
        }
    }

    /**
     * @param float $total
     * @param SaleRequest $request
     * @param Sale $sale
     * @return void
     */
    private function updateSaleTotals(float $total, SaleRequest $request, Sale $sale)
    {
        $discount = $this->calculateDiscount($request, $total);
        $net = $total - $discount;

        if($request->payment_type == PaymentTypeEnum::debt->value){
            $paid = $request->payment_amount;
        }else {
            $paid = $net;
        }

        $remaining = $net - $paid;
        $sale->total = $total;
        $sale->discount = $discount;
        $sale->net_amount = $net;
        $sale->paid_amount = $paid;
        $sale->remaining_amount = $remaining;
        $sale->save();
    }


}
