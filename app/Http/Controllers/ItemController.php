<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Enums\ItemStatusEnum;
use App\Models\Category;
use App\Models\Unit;
use App\Http\Requests\admin\ItemRequest;

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
        return view('admin.items.create', compact('ItemStatus', 'categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request)
    {
        Item::create($request->validated());
        session()->flash('success', 'Item created successfully.');
        return redirect()->route('admin.items.index');
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
