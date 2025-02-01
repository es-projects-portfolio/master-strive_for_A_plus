<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return redirect('/login');
    }
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('auth')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/admin/create-course', [AdminController::class, 'createCourse'])->name('admin.createCourse');
        Route::post('/admin/create-section', [AdminController::class, 'createSection'])->name('admin.createSection');
        Route::post('/admin/assign-student-to-section', [AdminController::class, 'assignStudentsToSection'])->name('admin.assignStudentToSection');
    });
});

require __DIR__.'/auth.php';
