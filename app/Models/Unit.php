<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\UnitStatusEnum;

class Unit extends Model 
{

    protected $table = 'units';
    public $timestamps = true;
    protected $fillable = array('name', 'status');

    protected $casts = [
        'status' => UnitStatusEnum::class,
    ];

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

}