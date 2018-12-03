<?php

namespace App\Domains\CSV;

use App\Domains\Category\Category;
use App\Domains\Product\Product;
use Illuminate\Database\Eloquent\Collection;

class CSVImport
{
    public function synchronizeCSV()
    {
        /** @var Collection $csvs */
        $csvs = CSV
            ::where('synced', false)
            ->get();

        \DB::transaction(function () use ($csvs) {
            $csvs->map(function (CSV $csv) {
                $file = storage_path(sprintf('%s%s', $csv->path, $csv->name));
                $fileOpen = $this->openFile($file);
                $this->readFileAndInsertData($fileOpen);
                $csv->synced = true;
                $csv->save();
            });
        });
    }

    private function openFile($file)
    {
        return fopen($file, 'r');
    }

    private function readFileAndInsertData($fileOpen)
    {
        $row = 0;
        $header = [];
        while (($csv = fgetcsv($fileOpen, '', ';', '"', '\\')) !== false) {
            if ($row === 0) {
                $header = $csv;
                $row++;
                continue;
            }

            $productData = array_combine($header, $csv);
            $this->createProduct($productData);
        }
    }

    private function createProduct($productData)
    {
        /** @var Category $category */
        $category = Category::firstOrNew(['name' => $productData['category_name']]);
        $category->save();

        $product = new Product();
        $product->name = $productData['product_name'];
        $product->price = $productData['price'];
        $product->description = $productData['description'];
        $product->category_id = $category->id;
        $product->save();
    }
}
