<?php

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

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/doctor/detail/{user}', [App\Http\Controllers\DoctorController::class, 'detail']);

Route::post('/consult/detail/{user}/data-form', [App\Http\Controllers\ConsultController::class, 'create'])->name('consult.create');
Route::post('/consult/{user}/complete/{consult}', [App\Http\Controllers\ConsultController::class, 'complete'])->name('consult.complete');

Route::get('/transaction-history', [App\Http\Controllers\TransactionController::class, 'index'])->name('transaction.history');
Route::get('/transaction-history/detail/{transaction}', [App\Http\Controllers\TransactionController::class, 'detail'])->name('transaction.history.detail');

Route::get('/consult/detail/chat/{consult}', [App\Http\Controllers\ConsultController::class, 'consultChat'])->name('consult.detail.chat');
// Route::get('/chat', [App\Http\Controllers\ChatsController::class, 'index']);
Route::get('/consult/messages/list/{consult}', [App\Http\Controllers\ConsultController::class, 'fetchMessages']);
Route::post('/consult/messages/send', [App\Http\Controllers\ConsultController::class, 'sendMessage']);

// Route::get('/messages', [App\Http\Controllers\ChatsController::class, 'fetchMessages']);
// Route::post('/messages', [App\Http\Controllers\ChatsController::class, 'sendMessage']);
