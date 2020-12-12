<?php

use App\Http\Controllers\LeagueController;
use Illuminate\Support\Facades\Route;

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

Route::get('/',[LeagueController::class, 'index'])->name('league.index');
Route::get('/{league}',[LeagueController::class, 'show'])->name('league.show');
Route::get('/{league}/run/{week}',[LeagueController::class, 'run'])->name('league.run');
