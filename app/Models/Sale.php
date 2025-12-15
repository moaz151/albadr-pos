<?php

namespace App\Models;

use App\Enums\SaleTypeEnum;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PaymentTypeEnum;
use App\Enums\DiscountTypeEnum;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Sale extends Model 
{

    protected $table = 'sales';
    public $timestamps = true;
    protected $fillable = array(
        'total', 'discount', 'discount_type', 'shipping_cost', 'net_amount', 'paid_amount', 'remaining_amount',
        'invoice_number', 'payment_type', 'client_id', 'safe_id', 'warehouse_id', 'user_id', 'type');

    protected $casts = [
        'type' => SaleTypeEnum::class,
        'payment_type' => PaymentTypeEnum::class,
    ];

   

    public function safeTransaction()
    {
        return $this->morphMany('App\Models\SafeTransaction', 'reference');
    }

    public function clientAccountTransaction()
    {
        return $this->morphMany('App\Models\ClientAccountTransaction', 'reference');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function safe()
    {
        return $this->belongsTo('App\Models\Safe');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function items()
    {
        return $this->morphToMany('App\Models\Item', 'itemable')
        ->withPivot('unit_price', 'quantity', 'total_price' , 'notes');
    }

}