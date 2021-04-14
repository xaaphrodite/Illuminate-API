<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\IllumiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| Route::middleware('auth:api')->get('/user', function (Request $request) {
|     return $request->user();
| });
*/

//? Register
// Route::PATCH('/illuminate/licensed/',[IlluminateController::class,'register']);
//? Login
// Route::POST('/illuminate/licensed/',[IlluminateController::class,'login']);
//? Read
// Route::GET('/illuminate/licensed/',[IlluminateController::class,'show']);
//? Generate Token
// Route::POST('/illuminate/generate/',[IlluminateController::class,'generate']);

//? Secure URL
// Route::middleware(['auth:sanctum'])->group(function(){
  //? Update
  // Route::PUT('/illuminate/licensed/',[IlluminateController::class,'update']);
  //? Delete
  // Route::DELETE('/illuminate/licensed/',[IlluminateController::class,'destroy']);
  //? Revoke
  // Route::DELETE('/illuminate/generate/',[IlluminateController::class,'revoke']);
// });


//! Improvement ------------------------------------------------------------------------------------------------------------------

//? Portal
Route::POST('/illuminate/portal/{params?}',[IllumiController::class,'poke']);

//? Secure URL
Route::middleware(['auth:sanctum'])->group(function(){
  Route::GET('/illuminate/secure/{params?}',[IllumiController::class,'invoke']);
  Route::POST('/illuminate/secure/{params?}',[IllumiController::class,'invoke']);
});