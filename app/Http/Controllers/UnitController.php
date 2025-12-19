<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Enums\UnitStatusEnum;
use App\Http\Requests\admin\UnitRequest;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-Unit')->only(['index']);
        $this->middleware('can:create-Unit')->only(['create', 'store']);
        $this->middleware('can:view-Unit')->only(['show']);
        $this->middleware('can:edit-Unit')->only(['edit', 'update']);
        $this->middleware('can:delete-Unit')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::paginate(10);
        return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $UnitStatus = UnitStatusEnum::labels();
        return view('admin.units.create', compact('UnitStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitRequest $request)
    {
        Unit::create($request->all());
        session()->flash('success', 'Unit created successfully.');
        return redirect()->route('admin.units.index');
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
        $unit = Unit::findOrFail($id);
        $UnitStatus = UnitStatusEnum::labels();
        return view('admin.units.edit', compact('unit', 'UnitStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitRequest $request, string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->update($request->all());
        session()->flash('success', 'Unit updated successfully.');
        return redirect()->route('admin.units.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        session()->flash('success', 'Unit deleted successfully.');
        return redirect()->route('admin.units.index');
    }
}
