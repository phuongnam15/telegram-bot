<?php

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

Route::get('/config', function () {
    return view('config_content');
});
Route::get('/', function () {
    return view('list_content');
});
Route::get('/update/{id}', function () {
    return view('update_content');
});
Route::get('/group', function () {
    return view('group');
});
Route::get('/bot', function () {
    return view('bot');
});
