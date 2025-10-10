<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model 
{
    use HasFactory;
    protected $table = 'clients';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'email', 'phone', 'address', 'balance', 'status', 'registered_via');

    protected $casts = [
        'registered_via' => \App\Enums\ClientRegistrationEnum::class,
        'status' => \App\Enums\ClientStatusEnum::class,
    ];

}