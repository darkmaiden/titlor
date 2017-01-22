<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome')->with(['content' => 'Welcome view']);
});

Route::get('/companies', function() {
    return response("Companies page");
});

Route::get('/companies/company{id}', function($id) {
    if(!is_numeric($id)) {
        abort(404);
    }
    return response("Company".$id." page");
});

Route::get('/companies/company{company_id}/product{product_id}', function($company_id, $product_id) {
    if(!is_numeric($company_id) || !is_numeric($product_id)) {
        abort(404);
    }
    return response("Company".$company_id." product".$product_id." page");
});