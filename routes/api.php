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



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });  
    Route::get('/todos', 'TodosController@index');
    Route::post('create/todos', 'TodosController@store');
    Route::patch('update/todos/{todo}', 'TodosController@update');
    Route::delete('delete/todos/{todo}', 'TodosController@destroy');
    Route::patch('todosCheckAll', 'TodosController@checkAll');
    Route::delete('todosDeleteCompleted', 'TodosController@destroyCompleted');
});
