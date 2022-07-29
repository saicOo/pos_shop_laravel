<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
	/** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
	Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function(){
        Route::get('/','DashboardController@index');
        // user routes
        Route::resource('users', 'UserController')->except(['show']);
        // category routes
        Route::resource('categories', 'CategoryController')->except(['show']);
        // product routes
        Route::resource('products', 'ProductController')->except(['show']);
        // client routes
        Route::resource('clients', 'ClientController')->except(['show']);
        Route::resource('clients.orders', 'Client\OrderController')->except(['show']);
        // orders routes
        Route::resource('orders', 'OrderController')->except(['show']);
        Route::get('/order/{order}/products', 'OrderController@products')->name('orders.products');
    });

});
/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/
