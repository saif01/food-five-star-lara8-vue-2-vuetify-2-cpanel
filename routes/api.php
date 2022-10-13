<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::namespace('App\Http\Controllers\Food\API')->group(function(){
    Route::get('/smartsoft_invoice', 'StoreController@getInvoiceData');
    Route::get('/smartsoft_invoice_sync/{start}/{end}', 'StoreController@invoiceSync');
    Route::get('/smartsoft_product_price_sync', 'ProductPriceController@productPriceSync');
    Route::get('/smartsoft_product/{code}', 'ProductController@index');
    // outlet
     Route::get('/smartsoft_outlet_sync', 'OutletController@outlet');
});




// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
