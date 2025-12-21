<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Module1\DashboardController;
use App\Http\Controllers\Module1\AdministratorController;
use App\Http\Controllers\ManageCourseController;
use App\Http\Controllers\ManageContentController;
use App\Http\Controllers\ManageAssessmentController;

/*
|--------------------------------------------------------------------------
| MODULE 1 – AUTH & DASHBOARD
|--------------------------------------------------------------------------
*/

// Root
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login', fn () => view('Module1.auth.login'))->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Dashboard
Route::get('/home', [DashboardController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| MODULE 1 – ADMIN (Teachers)
|--------------------------------------------------------------------------
*/

Route::get('/administrator/teacherslist',
    [AdministratorController::class, 'showTeachersList']
)->name('administrator.teacherslist');

Route::get('/administrator/addteacher',
    [AdministratorController::class, 'showAddTeacherForm']
)->name('administrator.addteacher');

Route::post('/administrator/addteacher',
    [AdministratorController::class, 'storeTeacher']
)->name('administrator.addteacher.post');

Route::get('/administrator/teachers/{id}/edit',
    [AdministratorController::class, 'editTeacher']
)->name('administrator.teachers.edit');

Route::put('/administrator/teachers/{id}',
    [AdministratorController::class, 'updateTeacher']
)->name('administrator.teachers.update');

Route::delete('/administrator/teachers/{id}',
    [AdministratorController::class, 'destroyTeacher']
)->name('administrator.teachers.destroy');

/*
|--------------------------------------------------------------------------
| MODULE 2 – MANAGE COURSE
|--------------------------------------------------------------------------
*/

// Admin
Route::get('/module2/admin',
    [ManageCourseController::class, 'indexAdmin']
)->name('module2.indexAdmin');

Route::put('/module2/{id}/approve',
    [ManageCourseController::class, 'approve']
)->name('module2.approve');

Route::put('/module2/{id}/reject',
    [ManageCourseController::class, 'reject']
)->name('module2.reject');

// Student
Route::middleware(['auth'])->group(function () {
    Route::get('/module2/student',
        [ManageCourseController::class, 'indexStudent']
    )->name('module2.indexStudent');

    Route::get('/module2/student/{id}',
        [ManageCourseController::class, 'showStudent']
    )->name('module2.showStudent');
});

Route::resource('module2', ManageCourseController::class);

/*
|--------------------------------------------------------------------------
| MODULE 3 – MANAGE CONTENT (TEACHER)
|--------------------------------------------------------------------------
*/

// Teacher entry (Form → Course list)
Route::get('/module3',
    [ManageContentController::class, 'module3']
)->name('module3.index');

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

/*
|--------------------------------------------------------------------------
| MODULE 3 – CONTENT (STUDENT) ✅ FIXED
|--------------------------------------------------------------------------
*/

// Student → Course list (Content menu)
Route::get('/student/module3',
    [ManageContentController::class, 'studentCourses']
)->name('student.module3.courses');

// Student → View contents of a course
Route::get('/student/course/{course}/content',
    [ManageContentController::class, 'studentContents']
)->name('student.module3.contents');

/*
|--------------------------------------------------------------------------
| MODULE 4 – ASSESSMENT
|--------------------------------------------------------------------------
*/

Route::get('/module4',
    [ManageAssessmentController::class, 'index']
)->name('module4.index');
