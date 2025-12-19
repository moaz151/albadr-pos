<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Client;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view-reports')->only([
            'itemTransactions',
            'salesReports',
            'salesReportsPrint',
            'salesReportsPdf',
            'salesReportsExcel'
        ]);
    }

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

    /**
     * Get sales data with filters (shared logic)
     */
    private function getSalesData(Request $request)
    {
        return Sale::with('items', 'client', 'warehouse', 'items.category')
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
            ->get();
    }

    /**
     * Print sales reports
     */
    public function salesReportsPrint(Request $request)
    {
        $sales = $this->getSalesData($request);
        $items = Item::all();
        $clients = Client::all();
        
        return view('admin.reports.sales-reports-print', compact('sales', 'items', 'clients'));
    }

    /**
     * Export sales reports to PDF
     */
    public function salesReportsPdf(Request $request)
    {
        $sales = $this->getSalesData($request);
        $items = Item::all();
        $clients = Client::all();
        
        $pdf = Pdf::loadView('admin.reports.sales-reports-pdf', compact('sales', 'items', 'clients'));
        $filename = 'sales-reports-' . date('Y-m-d-His') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export sales reports to Excel (CSV format)
     */
    public function salesReportsExcel(Request $request)
    {
        $sales = $this->getSalesData($request);
        
        $filename = 'sales-reports-' . date('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            
            // Helper function to escape CSV values properly
            $escapeCsv = function($value) {
                if (is_null($value)) {
                    return '';
                }
                // Convert to string
                $value = (string) $value;
                // If contains comma, newline, or quote, wrap in quotes and escape quotes
                if (strpos($value, ',') !== false || strpos($value, "\n") !== false || strpos($value, '"') !== false) {
                    $value = '"' . str_replace('"', '""', $value) . '"';
                }
                return $value;
            };
            
            // Write headers
            $headerRow = [
                __('trans.item_code'),
                __('trans.item_name'),
                __('trans.description'),
                __('trans.category'),
                __('trans.warehouse'),
                __('trans.sold_quantity'),
                __('trans.total_sales_amount'),
                __('trans.return_quantity'),
                __('trans.total_returns_amount'),
                __('trans.net_quantity'),
            ];
            fwrite($file, implode(',', array_map($escapeCsv, $headerRow)) . "\n");

            // Data rows
            foreach ($sales as $sale) {
                $firstItem = $sale->items->first();
                if (!$firstItem) {
                    continue;
                }

                $soldQuantity = $firstItem->pivot->quantity ?? 0;
                
                // Find returns for this sale
                $returnQuantity = 0;
                $totalReturnsAmount = 0;
                if ($sale->isSale()) {
                    $returns = Sale::where('type', \App\Enums\SaleTypeEnum::return->value)
                        ->where('client_id', $sale->client_id)
                        ->whereHas('items', function($q) use ($firstItem) {
                            $q->where('items.id', $firstItem->id);
                        })
                        ->get();
                    $returnQuantity = $returns->sum(function($return) use ($firstItem) {
                        $returnItem = $return->items->where('id', $firstItem->id)->first();
                        return $returnItem ? $returnItem->pivot->quantity : 0;
                    });
                    $totalReturnsAmount = $returns->sum('net_amount') ?? 0;
                }
                
                $netQuantity = $soldQuantity - $returnQuantity;
                $totalSalesAmount = $sale->net_amount ?? $sale->total ?? 0;

                $description = ($sale->isSale() ? __('trans.sale_invoice') : __('trans.return_invoice')) .
                    ' - ' . __('trans.client_name') . ': ' . ($sale->client->name ?? '');

                // Build row with proper escaping
                $row = [
                    $firstItem->item_code ?? '',
                    $firstItem->name ?? '',
                    $description,
                    $firstItem->category->name ?? '',
                    $sale->warehouse->name ?? '',
                    $soldQuantity,
                    $totalSalesAmount,
                    $returnQuantity,
                    $totalReturnsAmount,
                    $netQuantity,
                ];
                
                fwrite($file, implode(',', array_map($escapeCsv, $row)) . "\n");
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
