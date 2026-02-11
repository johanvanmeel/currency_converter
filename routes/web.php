<?php

use App\Http\Controllers\Admin\IpRestrictionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CurrencyConverterController;
use Illuminate\Support\Facades\Route;

// Currency converter routes.
Route::get('/', [CurrencyConverterController::class, 'index'])->name('currency.index');
Route::get('/convert', [CurrencyConverterController::class, 'convert'])->name('currency.convert');

// Admin routes.
Route::middleware(['auth', 'restrict-ip'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // IP Restrictions.
    Route::get('/ips', [IpRestrictionController::class, 'index'])->name('ips.index');
    Route::post('/ips', [IpRestrictionController::class, 'store'])->name('ips.store');
    Route::delete('/ips/{allowedIp}', [IpRestrictionController::class, 'destroy'])->name('ips.destroy');
});

require __DIR__ . '/auth.php';
