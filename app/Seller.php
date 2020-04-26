<?php

namespace App;

use App\Scopes\SellerScope;

class Seller extends User
{
    public static $modelName = 'vendedor';

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
