<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\BookController;

Route::get('login', [AuthWebController::class,'showLoginForm'])->name('login');
Route::post('login', [AuthWebController::class,'login']);
Route::get('register', [AuthWebController::class,'showRegisterForm']);
Route::post('register', [AuthWebController::class,'register']);

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthWebController::class,'logout']);
    Route::resource('books', BookController::class)->only(['index','create','store']);
    Route::get('books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

});
