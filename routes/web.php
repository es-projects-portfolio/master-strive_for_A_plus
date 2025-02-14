<!-- #**
 * Web Routes
 * 
 * This file contains the route definitions for the web application.
 * 
 * Routes:
 * - GET /: Redirects to /dashboard if authenticated, otherwise to /materials.
 * - GET /dashboard: Displays the dashboard, requires authentication.
 * - GET /materials: Displays the list of materials.
 * 
 * Authenticated and Verified Routes:
 * - GET /profile: Displays the profile edit form.
 * - PATCH /profile: Updates the profile information.
 * - DELETE /profile: Deletes the profile.
 * 
 * Admin Routes:
 * - GET /admin: Displays the admin dashboard.
 * - POST /admin/create-course: Creates a new course.
 * - POST /admin/create-section: Creates a new section.
 * - POST /admin/assign-student-to-section: Assigns students to a section.
 * 
 * Material Routes:
 * - GET /materials/create: Displays the material creation form.
 * - POST /materials: Stores a new material.
 * - GET /materials/{material}: Displays a specific material.
 * - GET /materials/{material}/edit: Displays the material edit form.
 * - PATCH /materials/{material}: Updates a specific material.
 * - DELETE /materials/{material}: Deletes a specific material.
 * 
 * Additional Routes:
 * - Requires additional authentication routes from auth.php.
 */ -->

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Material\MaterialController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return redirect('/materials');
    }
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/create-course', [AdminController::class, 'createCourse'])->name('admin.createCourse');
    Route::post('/admin/create-section', [AdminController::class, 'createSection'])->name('admin.createSection');
    Route::post('/admin/assign-student-to-section', [AdminController::class, 'assignStudentsToSection'])->name('admin.assignStudentToSection');

    // Material routes
    Route::get('/materials/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');
    Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::patch('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');
});

require __DIR__.'/auth.php';
