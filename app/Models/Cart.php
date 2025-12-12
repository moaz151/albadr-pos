<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = array('client_id');

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }


}
