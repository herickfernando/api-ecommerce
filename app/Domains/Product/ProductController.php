<?php

namespace App\Domains\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ProductController
 * @package App\Domains\Product
 * @property ProductService $service
 */
class ProductController extends Controller
{
    public function store(ProductRequest $request)
    {
        try {
            $product = $this
                ->service
                ->create($request->toArray());
            return response(['id' => $product->id], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return \response(['errors' => [
                'error' => [
                    $exception->getMessage(),
                ]
            ]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(Product $product)
    {
        return response($product);
    }

    public function update(Product $product, ProductRequest $request)
    {
        try {
            $product = $this
                ->service
                ->update($product, $request->toArray());
            return response(['id' => $product->id]);
        } catch (\Exception $exception) {
            return \response(['errors' => [
                'error' => [
                    $exception->getMessage(),
                ]
            ]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}