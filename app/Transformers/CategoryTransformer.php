<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
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
    public function transform(Category $category)
    {
        return [
            'id' => (int) $category->id,
            'title' => (string) $category->name,
            'details' => (string) $category->description,
            'creationDate' => (string) $category->created_at,
            'lastChange' => (string) $category->updated_at,
            'deletedDate' => isset($category->deleted_at) ? $category->deleted_at : null,

            'links' => [
                'self' => route('categories.show', $category->id),
                'category.buyers' => route('categories.buyers.index', $category->id),
                'category.sellers' => route('categories.sellers.index', $category->id),
                'category.transactions' => route('categories.transactions.index', $category->id),
            ],
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'title' => 'name',
            'details' => 'description',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
