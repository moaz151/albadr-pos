<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\WarehouseStatusEnum;

class Warehouse extends Model 
{
    use HasFactory;

    protected $table = 'warehouses';
    public $timestamps = true;
    protected $fillable = array('name', 'description', 'status');

    protected $casts = [
        'status' => WarehouseStatusEnum::class,
    ];

	public function items()
    {
		return $this->morphToMany('\App\Models\Item', 'itemable')
        ->withPivot('quantity');
    }

}