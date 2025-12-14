<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ItemResource;
use App\Models\Item;
use App\Enums\ItemStatusEnum;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    use ApiResponse;
    public $resourceName = ItemResource::class;

    public function index(Request $request)
    {
        // Start with base query - only active items
        $query = Item::where('status', ItemStatusEnum::active->value)
            ->with(['category', 'unit', 'mainPhoto', 'gallery']);

        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Search functionality - search in name, item_code, and description
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Pagination - allow custom per_page, default to 15, max 100
        $perPage = min($request->get('per_page', 15), 100);
        $items = $query->paginate($perPage);

        return $this->paginatedResponseApi($items, "Items retrieved successfully");
    }

    public function show($id)
    {
        $item = Item::with(['category', 'unit', 'mainPhoto', 'gallery'])
            ->where('status', ItemStatusEnum::active->value)
            ->find($id);
            
        if($item) {
            return $this->responseModelApi($item, "Item retrieved successfully");
        } else {
            return $this->apiErrorMessage("Item not found", 404);
        }
    }

    public function byCategory($categoryId)
    {
        $items = Item::where('status', ItemStatusEnum::active->value)
            ->where('category_id', $categoryId)
            ->with(['category', 'unit', 'mainPhoto'])
            ->paginate(request()->get('per_page', 15));
            
        return $this->paginatedResponseApi($items, "Items retrieved successfully");
    }

    public function search(Request $request)
    {
        $searchTerm = $request->get('q', '');
        
        if (empty($searchTerm)) {
            return $this->apiErrorMessage("Search query is required", 400);
        }
        
        $items = Item::where('status', ItemStatusEnum::active->value)
            ->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
                //   ->orWhere('description', 'like', "%{$searchTerm}%");
            })
            ->with(['category', 'unit', 'mainPhoto'])
            ->paginate($request->get('per_page', 15));
            
        return $this->paginatedResponseApi($items, "Search results retrieved successfully");
    }
}
