<?php

namespace App;

class Seller extends User
{
    public static $modelName = 'vendedor';

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
