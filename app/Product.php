<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public static $modelName = 'producto';

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
