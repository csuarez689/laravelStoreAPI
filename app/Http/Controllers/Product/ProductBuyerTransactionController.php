<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];
        $this->validate($request, $rules);

        if ($buyer->id == $product->seller_id) {
            return $this->errorJsonResponse('El comprador debe ser diferente del vendedor', 409);
        }
        if (!$buyer->isVerified()) {
            return $this->errorJsonResponse('El comprador debe ser un usuario verificado', 409);
        }
        if (!$product->seller->isVerified()) {
            return $this->errorJsonResponse('El vendedor debe ser un usuario verificado', 409);
        }
        if (!$product->isAvailable()) {
            return $this->errorJsonResponse('El producto no se encuentra disponible', 409);
        }
        if ($request->quantity > $product->quantity) {
            return $this->errorJsonResponse('El stock del producto es insuficiente para realizar esta transacción', 409);
        }

        //para ejecutar una transaccion atomica en la bd
        return DB::transaction(function () use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();
            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);
            return $this->showOne($transaction, 201);
        });
    }
}
