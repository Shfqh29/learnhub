<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageAuthenticationController;
use App\Http\Controllers\ManageCourseController;
use App\Http\Controllers\ManageContentController;
use App\Http\Controllers\ManageAssessmentController;

// Module 1 (Authentication index)
Route::get('/module1', [ManageAuthenticationController::class, 'index'])->name('module1.index');

// ===========================
// REGISTER & LOGIN (from stash)
// ===========================
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// LOGIN
Route::get('/login', function () {
    return view('Module1.auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ADMIN ADD TEACHER
Route::get('/administrator/addteacher', [AdministratorController::class, 'showAddTeacherForm'])
    ->name('administrator.addteacher');

Route::post('/administrator/addteacher', [AdministratorController::class, 'storeTeacher'])
    ->name('administrator.addteacher.post');


// ===========================
// MODULE 2
// ===========================
Route::resource('module2', ManageCourseController::class);


// ===========================
// MODULE 3 — Ambik FULL CRUD version from your teammate
// ===========================
Route::get('/module3', [ManageContentController::class, 'module3'])->name('module3.index');

// Content CRUD
Route::get('/teacher/course/{course}/content', [ManageContentController::class, 'index'])->name('content.index');
Route::get('/teacher/course/{course}/content/create', [ManageContentController::class, 'create'])->name('content.create');
Route::post('/teacher/course/{course}/content', [ManageContentController::class, 'store'])->name('content.store');
Route::get('/teacher/content/{id}/edit', [ManageContentController::class, 'edit'])->name('content.edit');
Route::put('/teacher/content/{id}', [ManageContentController::class, 'update'])->name('content.update');
Route::delete('/teacher/content/{id}', [ManageContentController::class, 'destroy'])->name('content.destroy');

Route::get('/dashboard1', function () {
    return redirect()->route('module3.index');
})->name('dashboard1');


// ===========================
// MODULE 4
// ===========================
Route::get('/module4', [ManageAssessmentController::class, 'index'])->name('module4.index');


// ===========================
// DEFAULT ROUTE
// ===========================
Route::get('/', function () {
    return redirect('/dashboard');
});


// ===========================
// AUTH MIDDLEWARE (profile)
// ===========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
