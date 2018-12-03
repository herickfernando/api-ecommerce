<?php

namespace App\Domains\Product;

use App\Domains\CSV\CSV;
use App\Domains\CSV\CSVImport;
use App\Domains\CSV\CSVRequest;
use App\Domains\CSV\CSVService;
use App\Http\Controllers\Controller;
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
            return \response([
                'errors' => [
                    'error' => [
                        $exception->getMessage(),
                    ]
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(Product $product)
    {
        return response($product->load('images'));
    }

    public function update(Product $product, ProductRequest $request)
    {
        try {
            $product = $this
                ->service
                ->update($product, $request->toArray());
            return response(['id' => $product->id]);
        } catch (\Exception $exception) {
            return \response([
                'errors' => [
                    'error' => [
                        $exception->getMessage(),
                    ]
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function uploadCSV(CSVRequest $request)
    {
        $service = new CSVService();
        /** @var CSV $csv */
        $csv = $service->create($request->toArray());
        return response(['id' => $csv->id], Response::HTTP_CREATED);
    }

    public function synchronizeCSV()
    {
        $service = new CSVImport();
        $service->synchronizeCSV();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
