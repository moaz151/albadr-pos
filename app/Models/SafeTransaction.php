<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\SafeTransactionTypeEnum;

class SafeTransaction extends Model 
{

    protected $table = 'safe_transactions';
    public $timestamps = true;
    protected $fillable = array('type', 'amount', 'description', 'balance_after', 'safe_id', 'user_id', 'date');

    protected $casts = [
        'type' => SafeTransactionTypeEnum::class,
    ];

    public function safe()
    {
        return $this->belongsTo('App\Models\Safe');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function reference()
    {
        return $this->morphTo();
    }

}