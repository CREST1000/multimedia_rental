<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DashboardController;

// Redirect to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Equipment routes
Route::resource('equipment', EquipmentController::class);

// Category routes
Route::resource('categories', CategoryController::class);

// Reservation routes with additional status routes
Route::resource('reservations', ReservationController::class);
Route::post('/reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');
Route::post('/reservations/{reservation}/complete', [ReservationController::class, 'complete'])->name('reservations.complete');
Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');