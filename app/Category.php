<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    public static $modelName = 'categoria';

    protected $fillable = [
        'name',
        'description',
    ];

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}
