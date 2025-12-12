<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatusEnum;
use App\Models\Client;
use App\Models\Sale;
use App\Models\Item;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = array(
        'client_id',
        'order_number',
        'status',
        'payment_method',
        'price',
        'shipping_cost',
        'total_price',
        'shipping_name',
        'shipping_address',
        'shipping_phone',
        'notes',
        'sale_id'
    );

    protected $casts = [
        'status' => \App\Enums\OrderStatusEnum::class,
    ];

    public function items()
    {
        return $this->belongsToMany('App\Models\Item', 'order_items')->withPivot('unit_price', 'quantity', 'total_price');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

}