<?php

namespace App;

use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;

class Buyer extends User
{

    public static $modelName = 'comprador';
    public $transformer = BuyerTransformer::class;

    protected static function boot()
    {
        parent::boot();

        //se agrega scope para obtener aquellos usuarios que poseen transacciones
        //los cuales corresponderian a compradores
        static::addGlobalScope(new BuyerScope);
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
}
