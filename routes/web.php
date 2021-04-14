<?php

use App\Http\Controllers\IllumiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IlluminateController;

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

//? Front
Route::GET('/', [IllumiController::class, 'illuminate']);
Route::GET('/illumi', [IllumiController::class, 'illumi']);
