<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ClientAccountTransactionTypeEnum;

class ClientAccountTransaction extends Model
{
    protected $table = 'client_account_transactions';
    public $timestamps = true;

    protected $fillable = [
        'credit',
        'debit',
        'balance',
        'client_id',
        'user_id',
        'description',
        'balance_after',
        'reference_id',
        'reference_type',
    ];

    protected $casts = [
        'credit' => 'decimal:2',
        'debit' => 'decimal:2',
        'balance' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
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
