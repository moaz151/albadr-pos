<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Enums\CategoryStatusEnum;
use App\Http\Requests\admin\CategoryRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-Category')->only(['index']);
        $this->middleware('can:create-Category')->only(['create', 'store']);
        $this->middleware('can:view-Category')->only(['show']);
        $this->middleware('can:edit-Category')->only(['edit', 'update']);
        $this->middleware('can:delete-Category')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('photo')->withCount('items')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoryStatus = CategoryStatusEnum::labels();
        return view('admin.categories.create', compact('categoryStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');                    
            $filename = time() . '_' . $file->getClientOriginalName(); 
            $file->storeAs('public/categories', $filename);    
            
            $category->photo()->create([
                'usage' => 'category_photo',                   
                'path' => 'categories/' . $filename,           
                'ext' => $file->getClientOriginalExtension(),  
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        $items = Item::where('category_id', $id)->paginate(10);
        return view('admin.categories.show', compact('category', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        $categoryStatus = CategoryStatusEnum::labels();
        return view('admin.categories.edit', compact('category', 'categoryStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        session()->flash('success', 'Category updated successfully.');
        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        session()->flash('success', 'Category deleted successfully.');
        return redirect()->route('admin.categories.index');
    }
}
