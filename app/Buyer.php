<?php

namespace App;

class Buyer extends User
{

    public static $modelName = 'comprador';

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
}
