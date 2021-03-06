<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only(['index']);
        $this->middleware('can:view,buyer')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
            ->get()
            ->pluck('product.categories')
            ->collapse() //une varias colecciones en una
            ->unique('id')
            ->values(); //resetea los indices de la coleccion

        return $this->showAll($categories);
    }
}
