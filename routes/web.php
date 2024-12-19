<?php

use App\Http\Controllers\Auth\EventController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware(['auth', 'verified'])->group(function() {

    Route::get('/dashboard', [DashboardController::class, 'openDashboardPage'])->name('dashboard');
    Route::resource('events', EventController::class);

    Route::prefix('profile')->as('profile.')->controller(ProfileController::class)->group(function() {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    Route::get('notifications/mark-as-read/{notification_id}/{user_id}', [NotificationController::class, 'markAsRead'])->name('mark.as.read');
    Route::get('notifications', [NotificationController::class, 'openAllNotifications'])->name('notifications.all');

});

Route::controller(HomeController::class)->group(function() {

    Route::as('site.')->group(function() {
        Route::get('/', 'openHomePage')->name('home');
        Route::get('events/{id}', 'openEventDetailsPage')->name('event.details');

        Route::get('thanku', 'openThankuPage')->name('thanku')->middleware('auth');
        Route::get('cancel', 'openCancelPage')->name('cancel')->middleware('auth');
    });

    Route::get('checkout', 'checkout')->name('checkout')->middleware('auth');

});

require __DIR__.'/auth.php';
