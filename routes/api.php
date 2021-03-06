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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/lists', 'App\Http\Controllers\ListsController', [
    'except' => ['edit', 'show', 'create']
  ])->middleware('auth:sanctum');

// GET 	/api/items 	        index (get all)	
// POST 	/api/items/store 	post a new item?
// PUT 	/api/items/{id} 	update 
// DELETE 	/api/items/{id} 	destroy 	

Route::resource('/items', 'App\Http\Controllers\ItemsController', [
  'except' => ['edit', 'show', 'create']
])->middleware('auth:sanctum');

Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
Route::get('/user', 'App\Http\Controllers\AuthController@me')->middleware('auth:sanctum');