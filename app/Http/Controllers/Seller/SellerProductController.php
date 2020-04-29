<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     * @param \App\Seller
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->successJsonResponse($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   \App\User $seller
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required|min:4|max:150',
            'description' => 'required|min:10|max:255',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image',
            'status' => 'required|in: 0,1',
        ];
        $this->validate($request, $rules);

        $data = $request->all();
        $data['status'] = $request->boolean('status');
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->successJsonResponse($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'name' => 'min:4|max:150',
            'description' => 'min:10|max:255',
            'quantity' => 'integer|min:1',
            'image' => 'image',
            'status' => 'in: 0,1',
        ];
        $this->validate($request, $rules);

        $this->checkSeller($seller, $product);

        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));

        if ($request->has('status')) {
            $product->status = $request->status;
            if ($product->isAvailable() && $product->categories()->count() == 0) {
                return $this->errorJsonResponse('Un producto activo debe tener al menos una categoria', 409);
            }
        }
        if ($request->hasFile('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }
        if ($product->isClean()) {
            return $this->errorJsonResponse('No hay datos que actualizar', 422);
        }
        $product->save();
        return $this->successJsonResponse($product->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);
        $product->delete();
        Storage::delete($product->image);
        return $this->successJsonResponse(['id' => $product->id]);
    }

    private function checkSeller(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpException(422, 'El vendedor no es el dueño de este producto');
        }
    }
}
