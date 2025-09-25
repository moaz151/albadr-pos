<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model 
{

    protected $table = 'files';
    public $timestamps = true;
    protected $fillable = array('usage', 'path', 'ext', 'fileable');

}