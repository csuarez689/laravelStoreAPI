<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
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
    public function transform(Product $product)
    {
        return [
            'id' => (int) $product->id,
            'title' => (string) $product->name,
            'details' => (string) $product->description,
            'stock' => (int) $product->quantity,
            'available' => (string) $product->status,
            'picture' => url("images/{$product->image}"),
            'seller' => (int) $product->seller_id,
            'creationDate' => (string) $product->created_at,
            'lastChange' => (string) $product->updated_at,
            'deletedDate' => isset($product->deleted_at) ? $product->deleted_at : null,

            'links' => [
                'self' => route('products.show', $product->id),
                'seller' => route('sellers.show', $product->seller_id),
                'product.buyers' => route('products.buyers.index', $product->id),
                'product.categories' => route('products.categories.index', $product->id),
                'product.transactions' => route('products.transactions.index', $product->id),
            ],
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'title' => 'name',
            'details' => 'description',
            'stock' => 'quantity',
            'available' => 'status',
            'picture' => 'image',
            'seller' => 'seller_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
