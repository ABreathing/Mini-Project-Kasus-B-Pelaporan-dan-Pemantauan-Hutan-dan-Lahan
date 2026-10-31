<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\RekapController;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/buat', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/laporan/{report}', [ReportController::class, 'show'])->whereNumber('report')->name('reports.show');
    Route::get('/laporan/{report}/edit', [ReportController::class, 'edit'])->whereNumber('report')->name('reports.edit');
    Route::put('/laporan/{report}', [ReportController::class, 'update'])->whereNumber('report')->name('reports.update');
    Route::delete('/laporan/{report}', [ReportController::class, 'destroy'])->whereNumber('report')->name('reports.destroy');
    Route::patch('/laporan/{report}/status', [ReportController::class, 'updateStatus'])->whereNumber('report')->name('reports.status');
    Route::post('/laporan/{report}/teruskan', [ReportController::class, 'forward'])->whereNumber('report')->name('reports.forward');
    Route::get('/laporan/{report}/bukti', [ReportController::class, 'evidence'])->whereNumber('report')->name('reports.evidence');
    Route::get('/areas/{area}/ringkasan', [AreaController::class, 'ringkasan'])->whereNumber('area')->name('areas.ringkasan');
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');
});