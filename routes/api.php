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

Route::group(['middleware' => ['cors', 'json.response']], function () {

    // public routes
    Route::post('/login', 'Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register','Auth\ApiAuthController@register')->name('register.api');
    
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');
    Route::get('/get_records', 'API\ManageIncomeController@getRecords');
    Route::post('/add_record', 'API\ManageIncomeController@add');

    Route::get('/get_income_total', 'API\ManageIncomeController@getTotal');
    Route::get('/get_incomes', 'API\SaveIncomeController@index');
    Route::get('/get_income_info', 'API\SaveIncomeController@incomInfo');
    Route::post('/save_income', 'API\SaveIncomeController@create');
    Route::post('/save_wish_item', 'API\WishListController@create');
    Route::get('/get_wish_items', 'API\WishListController@index');
    Route::get('/get_wish_items_uncomplete', 'API\WishListController@uncomplete');
    Route::put('/update_wish_item', 'API\WishListController@update');
    Route::delete('/delete_wish_item/{id}', 'API\WishListController@delete');
    Route::get('/articles', 'ArticleController@index')->name('articles');
});

