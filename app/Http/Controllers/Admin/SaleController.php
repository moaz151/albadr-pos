<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Safe;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Item;
use App\Enums\SafeStatusEnum;
use App\Enums\UnitStatusEnum;
use App\Enums\CatStatusEnum;
use App\Enums\ItemStatusEnum;

class SaleController extends Controller
{
    public function create()
    {
        $clients = Client::all();
        $safes = Safe::where('status', SafeStatusEnum::active)->get();
        $units = Unit::where('status', UnitStatusEnum::active)->get();
        $items = Item::where('status', ItemStatusEnum::active)->get();
        return view('admin.sales.create',
         compact('clients', 'safes', 'units', 'items'));
    }

    public function store(Request $request)
    {
        // // Validate the request data
        // $request->validate([
        //     'invoice_number' => 'required|unique:sales,invoice_number',
        //     'sale_date' => 'required|date',
        //     'client_id' => 'required|exists:clients,id',
        //     'safe_id' => 'required|exists:safes,id',
        //     // Add other validation rules as needed
        // ]);

        // // Create a new sale record
        // $sale = new Sale();
        // $sale->invoice_number = $request->input('invoice_number');
        // $sale->sale_date = $request->input('sale_date');
        // $sale->client_id = $request->input('client_id');
        // $sale->safe_id = $request->input('safe_id');
        // // Set other sale attributes as needed
        // $sale->save();

        // // Redirect to a relevant page with a success message
        // return redirect()->route('admin.sales.index')
        //                  ->with('success', 'Sale created successfully.');
    }
}
