<?php

namespace App;

use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    public static $modelName = 'producto';
    public $transformer = ProductTransformer::class;

    const AVAILABLE_PRODUCT = true;
    const UNAVAILABLE_PRODUCT = false;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];

    protected $hidden = [
        'pivot',
    ];

    public function isAvailable()
    {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function seller()
    {
        return $this->belongsTo('App\Seller');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
}
