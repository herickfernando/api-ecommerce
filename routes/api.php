<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route
    ::middleware(['jwt.auth'])
    ->prefix('backoffice')
    ->group(function () {
        Route::get('/categories/all', 'Category\CategoryController@all');

        Route::get('/products/sync', 'Product\ProductController@synchronizeCSV');

        Route::apiResource('/products', 'Product\ProductController');

        Route::post('/products/upload-csv', 'Product\ProductController@uploadCSV');
    });

Route::post('/auth', 'Auth\AuthController');
