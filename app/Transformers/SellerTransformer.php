<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
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
    public function transform(Seller $seller)
    {
        return [
            'id' => (int) $seller->id,
            'name' => (string) $seller->name,
            'email' => (string) $seller->email,
            'isVerified' => (string) $seller->verified,
            'creationDate' => (string) $seller->created_at,
            'lastChange' => (string) $seller->updated_at,
            'deletedDate' => isset($seller->deleted_at) ? $seller->deleted_at : null,
            'links' => [
                'self' => route('sellers.show', $seller->id),
                'self.user' => route('users.show', $seller->id),
                'seller.buyers' => route('sellers.buyers.index', $seller->id),
                'seller.categories' => route('sellers.categories.index', $seller->id),
                'seller.products' => route('sellers.products.index', $seller->id),
                'seller.transactions' => route('sellers.transactions.index', $seller->id),
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
