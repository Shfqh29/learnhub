<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\ManageCourseController;
use App\Http\Controllers\ManageContentController;
use App\Http\Controllers\ManageAssessmentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Module1\DashboardController;
use App\Http\Controllers\Module1\AdministratorController;


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
Route::get('/home', [DashboardController::class, 'index'])->name('home');

// MANAGE TEACHERS
// TEACHERS 
Route::get('/administrator/teacherslist', [AdministratorController::class, 'showTeachersList'])
    ->name('administrator.teacherslist');

// ADMIN ADD TEACHER
Route::get('/administrator/addteacher', [AdministratorController::class, 'showAddTeacherForm'])
    ->name('administrator.addteacher');

Route::post('/administrator/addteacher', [AdministratorController::class, 'storeTeacher'])
    ->name('administrator.addteacher.post');

 // Edit form
Route::get('/administrator/teachers/{id}/edit', [AdministratorController::class, 'editTeacher'])
    ->name('administrator.teachers.edit');

// Update action
Route::put('/administrator/teachers/{id}', [AdministratorController::class, 'updateTeacher'])
    ->name('administrator.teachers.update');

// Delete Teacher
Route::delete('/administrator/teachers/{id}', [AdministratorController::class, 'destroyTeacher'])
    ->name('administrator.teachers.destroy');



// Module 2
//Admin
Route::get('/module2/admin', [ManageCourseController::class, 'indexAdmin'])
    ->name('module2.indexAdmin');

Route::put('/module2/{id}/approve', [ManageCourseController::class, 'approve'])
    ->name('module2.approve');

Route::put('/module2/{id}/reject', [ManageCourseController::class, 'reject'])
    ->name('module2.reject');


 // Student routes
Route::middleware(['auth'])->group(function () {

    Route::get('/module2/student', [ManageCourseController::class, 'indexStudent'])
        ->name('module2.indexStudent');

    Route::get('/module2/student/{id}', [ManageCourseController::class, 'showStudent'])
        ->name('module2.showStudent');

});  

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







