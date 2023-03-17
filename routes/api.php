<?php

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

use App\Http\Controllers\API\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('get-balance', [WalletController::class, 'getBalance'])->name('get_balance');
