<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $buyers = $category->products()
            ->whereHas('transactions')
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions') //solo transacciones para colapsarlas
            ->collapse() //en un array simple
            ->pluck('buyer') //ahora si me qdo con los compradores
            ->unique('id')
            ->values();

        return $this->showAll($buyers);
    }
}
