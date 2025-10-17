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
use App\Enums\ItemStatusEnum;
use App\Enums\DiscountTypeEnum;
use App\Enums\PaymentTypeEnum;
use App\Http\Requests\Admin\SaleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function create()
    {
        $clients = Client::all();
        $safes = Safe::where('status', SafeStatusEnum::active)->get();
        $units = Unit::where('status', UnitStatusEnum::active)->get();
        $items = Item::where('status', ItemStatusEnum::active)->get();
        $discountTypes = DiscountTypeEnum::labels();
        return view('admin.sales.create',
         compact('clients', 'safes', 'units', 'items', 'discountTypes'));
    }

    public function store(SaleRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $sale = auth()->user()->sales()->create($request->validated());

                $total = 0;

                foreach ($request->items as $lineItem) {
                    $itemId = $lineItem['id'];
                    $qty = $lineItem['qty'];
                    $notes = $lineItem['notes'] ?? null;

                    // Lock the item row to avoid race conditions when decrementing stock
                    $queriedItem = Item::where('status', ItemStatusEnum::active)
                        ->lockForUpdate()
                        ->findOrFail($itemId);

                    if ($qty > $queriedItem->quantity) {
                        throw ValidationException::withMessages([
                            'items' => [__('messages.insufficient_stock')],
                        ]);
                    }

                    $unitPrice = $queriedItem->price; // trust server-side price
                    $lineTotal = $unitPrice * $qty;

                    // Attach pivot row with correct structure
                    $sale->items()->attach($itemId, [
                        'unit_price' => $unitPrice,
                        'quantity' => $qty,
                        'total_price' => $lineTotal,
                        'notes' => $notes,
                    ]);

                    // Decrement item stock
                    $queriedItem->decrement('quantity', $qty);

                    $total += $lineTotal;
                }

                // Discounts
                if ($request->discount_type == DiscountTypeEnum::percentage->value) {
                    $discount = $total * ($request->discount / 100);
                } else {
                    $discount = (float) ($request->discount ?? 0);
                }

                $net = $total - $discount;

                // Paid/remaining
                if ($request->payment_type == PaymentTypeEnum::debt->value) {
                    $paid = (float) $request->payment_amount;
                } else {
                    $paid = $net;
                }
                $remaining = $net - $paid;

                $sale->total = $total;
                $sale->discount = $discount;
                $sale->net_amount = $net;
                $sale->paid_amount = $paid;
                $sale->remaining_amount = $remaining;
                $sale->save();
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return back()->with('success', __('trans.saved_successfully'));
    }
}
