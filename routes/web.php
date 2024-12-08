<?php

use App\Http\Controllers\Auth\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Auth\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware(['auth', 'verified'])->group(function() {

    Route::get('/dashboard', [DashboardController::class, 'openDashboardPage'])->name('dashboard');

    Route::resource('events', EventController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::get('/', [HomeController::class, 'openHomePage'])->name('site.home');
Route::get('events/{id}', [HomeController::class, 'openEventDetailsPage'])->name('site.event.details');
Route::get('checkout', [HomeController::class, 'checkout'])->name('checkout')->middleware('auth');

Route::get('thanku', [HomeController::class, 'openThankuPage'])->name('site.thanku');
Route::get('cancel', [HomeController::class, 'openCancelPage'])->name('site.cancel');



require __DIR__.'/auth.php';
