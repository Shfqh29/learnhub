<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\ManageAuthenticationController;
use App\Http\Controllers\ManageCourseController;
use App\Http\Controllers\ManageContentController;
use App\Http\Controllers\ManageAssessmentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Module1\DashboardController;
use App\Http\Controllers\Module1\AdministratorController;


/*
|--------------------------------------------------------------------------
| Your existing module routes (UNCHANGED)
|--------------------------------------------------------------------------
*/

// MODULE 1

// Root URL redirect to login
Route::get('/', function () {
    return redirect()->route('login'); // redirect to the login page
});

// REGISTER
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





//Route::get('/module1', [ManageAuthenticationController::class, 'index'])->name('module1.index');

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
    

Route::get('/dashboard1', function () {
    return redirect()->route('module3.index');  // you can change to module2 or homepage later
})->name('dashboard1');

/*
|--------------------------------------------------------------------------
| Your existing auth group (UNCHANGED, keep profile protected)
|--------------------------------------------------------------------------
*/


