<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MateriController as AdminMateriController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\KlasifikasiController as AdminKlasifikasiController;
use App\Http\Controllers\Admin\EvaluasiController as AdminEvaluasiController;
use App\Http\Controllers\Admin\DecisionTreeController as AdminDecisionTreeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check() && Auth::user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/materi/{bab}/{track?}/{step}', [MateriController::class, 'show'])->name('materi.show');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'ensure.admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('materi', AdminMateriController::class)->except(['show']);

    Route::resource('users', AdminUserController::class)->except(['show', 'create', 'store']);
    Route::patch('users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::patch('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [AdminReportController::class, 'exportCsv'])->name('reports.export');

    Route::get('klasifikasi', [AdminKlasifikasiController::class, 'index'])->name('klasifikasi.index');
    Route::post('klasifikasi/{user}', [AdminKlasifikasiController::class, 'storeOrUpdate'])->name('klasifikasi.store');
    Route::delete('klasifikasi/{user}', [AdminKlasifikasiController::class, 'destroy'])->name('klasifikasi.destroy');

    Route::resource('evaluasi', AdminEvaluasiController::class)->except(['show']);
    Route::get('decision-tree', [AdminDecisionTreeController::class, 'index'])->name('decision-tree.index');
    Route::view('rules', 'admin.rules.index')->name('rules.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
