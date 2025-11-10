<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Enums\ItemStatusEnum;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Http\Requests\admin\ItemRequest;
use App\Services\StockManageService;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $items = Item::with(['category', 'unit'])->paginate(10);
        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $units = Unit::all();
        $ItemStatus = ItemStatusEnum::labels();
        $warehouses = Warehouse::all();
        return view('admin.items.create', compact('ItemStatus', 'categories', 'units', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request)
    {
        DB::beginTransaction();
        $item = Item::create($request->validated());
        // Photo is optional
        if($request->hasFile('photo') && $request->file('photo')->isValid()){
            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $ext;
            $path = $file->storeAs('items', $fileName, 'public');
            $item->mainPhoto()->create([
                'usage' => 'item_photo',
                'path' => $path,
                'ext' => $ext,
            ]);
        }
        if($request->hasFile('gallery')){
            foreach($request->file('gallery') as $gallery){
                if($gallery && $gallery->isValid()) {
                    $ext = $gallery->getClientOriginalExtension();
                    $fileName = time() . '_' . uniqid() . '.' . $ext;
                    $path = $gallery->storeAs('items', $fileName, 'public');
                    $item->gallery()->create([
                        'usage' => 'item_gallery',
                        'path' => $path,
                        'ext' => $ext,
                    ]);
                }
            }
        }
        (new StockManageService)->initStock($item, $request->warehouse_id, $request->quantity);
        DB::commit();
        return to_route('admin.items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        $units = Unit::all();
        $ItemStatus = ItemStatusEnum::labels();
        return view('admin.items.edit', compact('item', 'ItemStatus', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemRequest $request, string $id)
    {
        $item = Item::findOrFail($id);
        $item->update($request->validated());
        session()->flash('success', 'Item updated successfully.');
        return redirect()->route('admin.items.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        session()->flash('success', 'Item deleted successfully.');
        return redirect()->route('admin.items.index');
    }
}
