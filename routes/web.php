<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\HistoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Guest routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Routes that will require authentication in Subphase 1.4
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // File routes
    Route::resource('files', FileController::class);

    // Borrowing routes
    Route::get('/borrow', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrow', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::put('/borrow/{id}/return', [BorrowingController::class, 'return'])->name('borrowings.return');

    // History routes
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
});
