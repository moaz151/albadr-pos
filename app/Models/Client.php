<?php

namespace App\Models;

use App\Enums\ClientRegistrationEnum;
use App\Enums\ClientStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ClientAccountTransaction;
use Laravel\Sanctum\HasApiTokens;

class Client extends User
{
    use HasFactory, HasApiTokens;
    protected $table = 'clients';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'email', 'phone', 'address', 'balance', 'status', 'registered_via');

    protected $casts = [
        'registered_via' => ClientRegistrationEnum::class,
        'status' => ClientStatusEnum::class,
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function accountTransactions()
    {
        return $this->hasMany(ClientAccountTransaction::class);
    }

}
