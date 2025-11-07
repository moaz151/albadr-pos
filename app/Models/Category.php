<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CategoryStatusEnum;

class Category extends Model 
{
    use HasFactory;
    protected $table = 'categories';
    public $timestamps = true;
    protected $fillable = ['name', 'status'];
    protected $casts = [
        'status' => CategoryStatusEnum::class,
    ];

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

    public function photo()
    {
        return $this->morphOne('App\Models\File', 'fileable')->where('usage', 'category_photo');
    }

    // public function casts(): array
    // {
    //     return ([
    //         'status' => CategoryStatusEnum::class,
    //         ]) ;
    // }

}