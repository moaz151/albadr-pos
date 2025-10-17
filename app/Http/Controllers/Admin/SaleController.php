<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Safe;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Item;
use App\Models\Sale;
use App\Enums\SafeStatusEnum;
use App\Enums\UnitStatusEnum;
use App\Enums\CatStatusEnum;
use App\Enums\ClientStatusEnum;
use App\Enums\ItemStatusEnum;
use App\Enums\DiscountTypeEnum;
use App\Enums\PaymentTypeEnum;
use App\Http\Requests\Admin\SaleRequest;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function create()
    {
        $clients = Client::Where('status', ClientStatusEnum::active)->get();
        $safes = Safe::where('status', SafeStatusEnum::active)->get();
        $units = Unit::where('status', UnitStatusEnum::active)->get();
        $items = Item::where('status', ItemStatusEnum::active)->get();
        $discountTypes = DiscountTypeEnum::labels();
        return view('admin.sales.create',
         compact('clients', 'safes', 'units', 'items', 'discountTypes'));
    }

    public function store(SaleRequest $request)
    {
        $sale = auth()->user()->sales()->create($request->validated());
        $total = 0;
        $discount = 0;
        $remaining = 0;
        
        foreach($request->items as $item){
            $queriedItem = Item::Where('status', ItemStatusEnum::active)->findOrFail($item['id']);
            $totalPrice = $queriedItem->price *$item['qty'];
            $sale->items()->attach([
                $items['id'] = [
                    'unit_price' => $item['price'],
                    'quantity' => $item['qty'],
                    'total_price' => $totalPrice,
                    'notes' => $item['notes'],
                ]
            ]);
            $total += $totalPrice;
        }
        if($request->discount_type == DiscountTypeEnum::percentage->value){
            $discount = $total * ($request->discount/100);
        }else {
            $discount = $request->discount;
        }
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
        // Deduct $paid from client balance
        DB::transaction(function () use ($request, $paid) {
            $client = Client::where('status', ClientStatusEnum::active)
                ->lockForUpdate()
                ->findOrFail($request->client_id);
            $client->decrement('balance', (float) $paid);
        });

        // Decrement Item Table
        $queriedItem->decrement('quantity', $item['qty']);
        
        return back()->with('success', __('trans.saved_successfully'));
    }
}
