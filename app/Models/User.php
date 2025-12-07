<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\SaleTypeEnum;
use App\Enums\UserStatusEnum;
use App\Models\Sale;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'username',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatusEnum::class,
        ];
    }

    /**
     * Get the sales for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales(): User|HasMany
    {
        return $this->hasMany(Sale::class)->where('type', SaleTypeEnum::sale->value);
    }

    public function returns(): User|HasMany
    {
        return $this->hasMany(Sale::class)->where('type', SaleTypeEnum::return->value);
    }
}
