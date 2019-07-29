<?php

use Illuminate\Http\Request;
//use Symfony\Component\Routing\Route;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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

//List Posts
Route::get('posts', 'PostController@index');

//Show Single Post
Route::get('posts/{id}', 'PostController@show');

//Add New Post
Route::post('posts', 'PostController@store');

//Update Post
Route::put('posts', 'PostController@store');

//Delete Post
Route::delete('posts/{id}', 'PostController@destroy');
