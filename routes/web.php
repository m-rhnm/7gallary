<?php

use App\Models\Category;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('products/all', function () {
    return view('frontend.products.all');
});



Route::get('admin/index', function () {
   return view('admin.index');
});
Route::get('admin/users', function () {
    return view('admin.users.user');
 });
 
