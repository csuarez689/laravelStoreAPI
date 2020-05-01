<?php

namespace App;

use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    public static $modelName = 'transacción';
    public $transformer = TransactionTransformer::class;

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id',
    ];

    public function buyer()
    {
        return $this->belongsTo('App\Buyer');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
