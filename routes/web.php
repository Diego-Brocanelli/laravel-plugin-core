<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/admin', function () {
    return view('welcome');
});

//if (in_array(env('APP_ENV'), ['local', 'testing']) && env('APP_DEBUG') === true) {
    
Route::namespace('App\Plugin\Core\Http\Controllers')->group(function(){
    Route::get('/core/grid', 'ExampleController@index')->name('example.grid');
    Route::get('/core/grid/data', 'ExampleController@indexDataProvider')->name('example.grid.data');
    Route::get('/core/form', 'ExampleController@create')->name('example.form');
    Route::get('/core/form/save', 'ExampleController@createService')->name('example.form.save');
});

//}
