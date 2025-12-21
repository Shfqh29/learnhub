<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageAuthenticationController;
use App\Http\Controllers\ManageCourseController;
use App\Http\Controllers\ManageContentController;
use App\Http\Controllers\ManageAssessmentController;

/*
|--------------------------------------------------------------------------
| Your existing module routes (UNCHANGED)
|--------------------------------------------------------------------------
*/

Route::get('/module1', [ManageAuthenticationController::class, 'index'])->name('module1.index');

Route::resource('module2', ManageCourseController::class);

Route::get('/module4', [ManageAssessmentController::class, 'index'])->name('module4.index');


/*
|--------------------------------------------------------------------------
| Module 3 – Manage Content (Teacher)
|--------------------------------------------------------------------------
*/

// Entry page for Module 3 (Form → Course list)
Route::get('/module3', [ManageContentController::class, 'module3'])
    ->name('module3.index');

// Course → Content
Route::get('/teacher/course/{course}/content',
    [ManageContentController::class, 'index']
)->name('content.index');

// Add Week (Title)
Route::get('/teacher/course/{course}/title/create',
    [ManageContentController::class, 'createTitle']
)->name('title.create');

Route::post('/teacher/course/{course}/title',
    [ManageContentController::class, 'storeTitle']
)->name('title.store');

// Add Content
Route::get('/teacher/course/{course}/content/create',
    [ManageContentController::class, 'create']
)->name('content.create');

Route::post('/teacher/course/{course}/content',
    [ManageContentController::class, 'store']
)->name('content.store');

// Edit / Update / Delete Content
Route::get('/teacher/content/{id}/edit',
    [ManageContentController::class, 'edit']
)->name('content.edit');

Route::put('/teacher/content/{id}',
    [ManageContentController::class, 'update']
)->name('content.update');

Route::delete('/teacher/content/{id}',
    [ManageContentController::class, 'destroy']
)->name('content.destroy');

// STUDENT VIEW (NO LOGIN YET)
Route::get('/student/module3', [ManageContentController::class, 'module3'])
    ->name('student.courses');

Route::get('/student/course/{course}/content',
    [ManageContentController::class, 'index']
)->name('student.content.index');


Route::get('/dashboard', function () {
    return redirect()->route('module3.index');  // you can change to module2 or homepage later
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Your existing auth group (UNCHANGED, keep profile protected)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
