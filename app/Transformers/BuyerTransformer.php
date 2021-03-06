<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
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
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'id' => (int) $buyer->id,
            'name' => (string) $buyer->name,
            'email' => (string) $buyer->email,
            'isVerified' => (string) $buyer->verified,
            'creationDate' => (string) $buyer->created_at,
            'lastChange' => (string) $buyer->updated_at,
            'deletedDate' => isset($buyer->deleted_at) ? $buyer->deleted_at : null,
            'links' => [
                'self' => route('buyers.show', $buyer->id),
                'self.user' => route('users.show', $buyer->id),
                'buyer.categories' => route('buyers.categories.index', $buyer->id),
                'buyer.products' => route('buyers.products.index', $buyer->id),
                'buyer.sellers' => route('buyers.sellers.index', $buyer->id),
                'buyer.transactions' => route('buyers.transactions.index', $buyer->id),
            ],
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
