<?php

namespace App;

use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public static $modelName = 'categoria';
    public $transformer = CategoryTransformer::class;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = [
        'pivot',
    ];

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}
