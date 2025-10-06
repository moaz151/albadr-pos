<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ItemStatusEnum;

class Item extends Model 
{

    protected $table = 'items';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'item_code','category_id', 'unit_id' , 'description', 'price', 'quantity', 'status', 'minimum_stock');
    protected $casts = [
        'status' => ItemStatusEnum::class,
    ];

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function gallery()
    {
        return $this->morphMany('App\Models\File', 'fileable')->where('usage', 'item_gallery');
    }

    public function sales()
    {
        return $this->morphedByMany('App\Models\Sale', 'itemable');
    }

    public function returns()
    {
        return $this->morphedByMany('App\Models\SaleReturn', 'itemable');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order', 'item_orders')->withPivot('unit_price', 'quantity', 'total_price');
    }

    public function mainPhoto()
    {
        return $this->morphOne('App\Models\File', 'fileable')->where('usage', 'item_photo');
    }

}