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

Route::get('/config/{id}', function () {
    return view('config_content');
});
Route::get('/content', function () {
    return view('list_content');
});
Route::get('/update/{id}', function () {
    return view('update_content');
});
Route::get('/group', function () {
    return view('group');
});
Route::get('/', function () {
    return view('bot');
});
Route::get('/phone', function () {
    return view('phone');
});
Route::get('/password', function () {
    return view('password');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
});
Route::get('/setting-bot/{id}', function ($id) {
    return view('setting_bot');
});
Route::get('/analytic/{id}', function () {
    return view('analytic');
});
