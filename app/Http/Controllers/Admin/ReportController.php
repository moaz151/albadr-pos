<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Client;
use App\Models\Sale;

class ReportController extends Controller
{
    public function itemTransactions(Request $request)
    {
        $items = Item::all();
        $clients = Client::all();

        $sales = Sale::with('items', 'client', 'warehouseTransactions', 'user')
            ->where(function($query) use($request){
                // Date from filter
                if ($request->filled('date_from')){
                    $query->whereDate('created_at', '>=', $request->date_from);
                }
                
                // Date to filter
                if ($request->filled('date_to')){
                    $query->whereDate('created_at', '<=', $request->date_to);
                }
                
                // Client filter
                if ($request->filled('client_id')){
                    $query->where('client_id', $request->client_id);
                }
                
                // Item filter
                if ($request->filled('item_id')){
                    $query->whereHas('items', function ($q) use ($request){
                        $q->where('items.id', $request->item_id);
                    });
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('admin.reports.item-transactions', compact('sales', 'items', 'clients'));
    }

    public function salesReports(Request $request)
    {
        $items = Item::all();
        $clients = Client::all();

        $sales = Sale::with('items', 'client', 'warehouseTransactions', 'user')
            ->where(function($query) use($request){
                // Date from filter
                if ($request->filled('date_from')){
                    $query->whereDate('created_at', '>=', $request->date_from);
                }
                
                // Date to filter
                if ($request->filled('date_to')){
                    $query->whereDate('created_at', '<=', $request->date_to);
                }

                // Client filter
                if ($request->filled('client_id')){
                    $query->where('client_id', $request->client_id);
                }
                
                // Item filter
                if ($request->filled('item_id')){
                    $query->whereHas('items', function ($q) use ($request){
                        $q->where('items.id', $request->item_id);
                    });
                }
            })->paginate();

        return view('admin.reports.sales-reports', compact('sales', 'items', 'clients'));
    }
}
