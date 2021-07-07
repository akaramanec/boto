<?php

use App\Http\Controllers\Admin\BotMessageController;
use App\Http\Controllers\Admin\BotSettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
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

Route::get('/import', [AdminProductController::class, 'fileImport'])->middleware(['auth', 'admin'])->name('file_import');

Route::post(Telegram::getAccessToken(), function () {
    Telegram::commandsHandler(true);
});

Route::group(['middleware' => 'auth', 'prefix' => '/dashboard'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('admin')->prefix('/bot-messages')->name('bot_messages.')->group(function () {
        Route::get('/', [BotMessageController::class, 'index'])->name('index');
        Route::post('/', [BotMessageController::class, 'store'])->name('store');
        Route::get('/{message}', [BotMessageController::class, 'edit'])->name('edit');
        Route::delete('/{message}', [BotMessageController::class, 'delete'])->name('delete');
        Route::match(['put', 'patch'],'/{message}', [BotMessageController::class, 'update'])->name('update');
    });

    Route::middleware('admin')->prefix('/bot-settings')->name('bot_settings.')->group(function () {
        Route::get('/', [BotSettingController::class, 'index'])->name('index');
        Route::post('/', [BotSettingController::class, 'store'])->name('store');
        Route::post('/setwebhook', [BotSettingController::class, 'setWebhook'])->name('set_webhook');
        Route::post('/getwebhookinfo', [BotSettingController::class, 'getWebhookInfo'])->name('get_webhook_info');
        Route::get('/test', [BotSettingController::class, 'test'])->name('test');
    });
});

require __DIR__.'/auth.php';
