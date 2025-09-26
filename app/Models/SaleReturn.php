<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model 
{

    protected $table = 'returns';
    public $timestamps = true;
    protected $fillable = array('return_number', 'return_amount', 'reason');

    public function safeTransaction()
    {
        return $this->morphMany('App\Models\SafeTransaction', 'reference');
    }

    public function items()
    {
        return $this->morphToMany('App\Models\User', 'itemable');
    }

}