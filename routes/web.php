<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DepartmentController;

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

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // User profile routes
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // File routes - Accessible only by File Admin
    Route::middleware(['check.role:admin'])->group(function () {
        Route::resource('files', FileController::class);
        Route::resource('departments', DepartmentController::class);
    });

    // Borrowing routes - Accessible by both File Admin and File Officer
    Route::middleware(['check.role:officer'])->group(function () {
        Route::get('/borrow', [BorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('/borrow', [BorrowingController::class, 'store'])->name('borrowings.store');
        Route::put('/borrow/{id}/return', [BorrowingController::class, 'return'])->name('borrowings.return');
    });

    // History routes - Accessible by both File Admin and File Officer
    Route::middleware(['check.role:officer'])->group(function () {
        Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    });
});
