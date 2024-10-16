<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('material', function () {
    return view('admin.material.list');
})->name('admin.material.list');
Route::get('supplier', function () {
    return view('admin.supplier.list');
})->name('admin.supplier.list');
Route::get('user', function () {
    return view('admin.user.list');
})->name('admin.user.list');
Route::get('admin', function () {
    return view('admin.index');
})->name('admin.index');
