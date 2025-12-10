<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageAuthenticationController;
use App\Http\Controllers\ManageCourseController;
use App\Http\Controllers\ManageContentController;
use App\Http\Controllers\ManageAssessmentController;


Route::get('/module1', [ManageAuthenticationController::class, 'index'])->name('module1.index');

Route::resource('module2', ManageCourseController::class);

Route::get('/module3', [ManageContentController::class, 'index'])->name('module3.index');

Route::get('/module4', [ManageAssessmentController::class, 'index'])->name('module4.index');


Route::get('/', function () {
    return redirect('/dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
