<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('auth.login'))->middleware('guest')->name('login');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/salle', SalleController::class)->withTrashed();
    Route::resource('/reservation', ReservationController::class)->withTrashed();

    Route::get('/salle/{salle}/restore', [SalleController::class, 'restore'])->withTrashed()->name('salle.restore');
    Route::get('/reservation/{reservation}/restore', [ReservationController::class, 'restore'])->withTrashed()->name('reservation.restore');
});

require __DIR__.'/auth.php';
