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
| Module 3 (TEMPORARY: allow without login)
| ONLY CHANGE: Module 3 routes are NOT inside auth group.
|--------------------------------------------------------------------------
*/

// Entry page for Module 3 (course list)
Route::get('/module3', [ManageContentController::class, 'module3'])
    ->name('module3.index');

// Content CRUD (course-based)
Route::get('/teacher/course/{course}/content', [ManageContentController::class, 'index'])
    ->name('content.index');

Route::get('/teacher/course/{course}/content/create', [ManageContentController::class, 'create'])
    ->name('content.create');

Route::post('/teacher/course/{course}/content', [ManageContentController::class, 'store'])
    ->name('content.store');

Route::get('/teacher/content/{id}/edit', [ManageContentController::class, 'edit'])
    ->name('content.edit');

Route::put('/teacher/content/{id}', [ManageContentController::class, 'update'])
    ->name('content.update');

Route::delete('/teacher/content/{id}', [ManageContentController::class, 'destroy'])
    ->name('content.destroy');
    

Route::get('/dashboard', function () {
    return redirect()->route('module3.index');  // you can change to module2 or homepage later
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Your existing auth group (UNCHANGED, keep profile protected)
|--------------------------------------------------------------------------
*/

/* Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';*/
