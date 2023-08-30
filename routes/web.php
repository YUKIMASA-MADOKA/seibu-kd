<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KadecDataController;
use App\Http\Controllers\PossDataController;
use App\Http\Controllers\PredDataController;

use App\Http\Controllers\IrateDataController;
use App\Http\Controllers\CraftDataController;
use App\Http\Controllers\Pred1DataController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [Pred1DataController::class,"index"])
   ->name("index_pred1");

Route::get('/test', [KadecDataController::class,"index"])
   ->name("index_test");
Route::get('/poss', [PossDataController::class,"index"])
   ->name("index_poss");
Route::get('/pred', [PredDataController::class,"index"])
   ->name("index_pred");

Route::get('/pred1', [Pred1DataController::class,"index"])
   ->name("index_pred1");
Route::get('/irate', [IrateDataController::class,"index"])
   ->name("index_irate");
Route::get('/auto', [IrateDataController::class,"auto"])
   ->name("auto_irate");
Route::get('/craft', [CraftDataController::class,"index"])
   ->name("index_craft");
Route::post('/craft', [CraftDataController::class,"index"])
   ->name("index_craft");
Route::post('/update', [CraftDataController::class,"update"])
   ->name("update_craft");

Route::post('/python', [PredDataController::class,"executePython"]);

