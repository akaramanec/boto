<?php

use App\Http\Controllers\BotMessageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Models\BotMessage;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/import', [ProductController::class, 'fileImport'])->middleware(['auth', 'admin']);

Route::group(['middleware' => 'auth', 'prefix' => '/dashboard'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(['middleware' => 'admin', 'prefix' => '/bot-messages'], function () {
        Route::get('/', [BotMessageController::class, 'index'])->name('dashboard.bot_messages');
    });
});

require __DIR__.'/auth.php';
