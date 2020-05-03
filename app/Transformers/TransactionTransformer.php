<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array"
     */
    public function transform(Transaction $transaction)
    {
        return [
            'id' => (int) $transaction->id,
            'quantity' => (int) $transaction->qunatity,
            'buyer' => (int) $transaction->buyer_id,
            'product' => (int) $transaction->product_id,
            'creationDate' => (string) $transaction->created_at,
            'lastChange' => (string) $transaction->updated_at,
            'deletedDate' => isset($transaction->deleted_at) ? $transaction->deleted_at : null,

            'links' => [
                'self' => route('transactions.show', $transaction->id),
                'product' => route('products.show', $transaction->product_id),
                'buyer' => route('buyers.show', $transaction->buyer_id),
                'transaction.categories' => route('transactions.categories.index', $transaction->id),
                'transaction.seller' => route('transactions.sellers.index', $transaction->id),
            ],
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'quantity' => 'quantity',
            'buyer' => 'buyer_id',
            'product' => 'product_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
