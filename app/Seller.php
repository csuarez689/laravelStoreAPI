<?php

namespace App;

use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;

class Seller extends User
{
    public static $modelName = 'vendedor';
    public $transformer = SellerTransformer::class;

    protected static function boot()
    {
        parent::boot();

        //se agrega scope para obtener aquellos usuarios que poseen transacciones
        //los cuales corresponderian a compradores
        static::addGlobalScope(new SellerScope);
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
