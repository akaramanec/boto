<?php

use App\Http\Controllers\BotMessageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
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

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/import', [ProductController::class, 'fileImport'])->middleware(['auth', 'admin'])->name('file_import');

Route::group(['middleware' => 'auth', 'prefix' => '/dashboard'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(['middleware' => 'admin', 'prefix' => '/bot-messages'], function () {
        Route::get('/', [BotMessageController::class, 'index'])->name('bot_messages');
        Route::post('/', [BotMessageController::class, 'store'])->name('bot_messages.store');
        Route::get('/{message}', [BotMessageController::class, 'edit'])->name('bot_messages.edit');
        Route::delete('/{message}', [BotMessageController::class, 'delete'])->name('bot_messages.delete');
        Route::match(['put', 'patch'],'/{message}', [BotMessageController::class, 'update'])->name('bot_messages.update');
    });
});

require __DIR__.'/auth.php';
