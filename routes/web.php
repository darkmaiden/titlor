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

// some routes with titles as examples

Route::get('/', function () {
    return view('welcome')->with(['content' => 'Welcome view', 'title' => Title::getTitleByUri('/')]);
});

Route::get('/companies', function() {
    return view("companies")->with(['content' => 'Companies view', 'title' => Title::getTitleByUri('/companies')]);
});

Route::get('/companies/company{id}', function($id) {
    return view("company")->with(['content' => 'Company'.$id.' view', 'title' => Title::getTitleByUri('/companies/company'.$id)]);
})->where(array('id' => '[0-9]+'));

Route::get('/companies/company{company_id}/product{product_id}', function($company_id, $product_id) {
    return view("companies_product")->with(['content' => 'Company'.$company_id.' product'.$product_id.'view',
        'title' => Title::getTitleByUri('/companies/company'.$company_id.'/product'.$product_id)]);
})->where(array('company_id' => '[0-9]+', 'product_id' => '[0-9]+'));