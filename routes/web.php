<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KadecDataController;
use App\Http\Controllers\PossDataController;
use App\Http\Controllers\PredDataController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [KadecDataController::class,"index"])
   ->name("index_test");

Route::get('/poss', [PossDataController::class,"index"])
   ->name("index_poss");

Route::get('/pred', [PredDataController::class,"index"])
   ->name("index_pred");

Route::post('/python', [PredDataController::class,"executePython"]);

