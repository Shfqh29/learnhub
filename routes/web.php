<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use App\Http\Controllers\Module1\DashboardController;
use App\Http\Controllers\Module1\AdministratorController;

use App\Http\Controllers\ManageCourseController;
use App\Http\Controllers\ManageContentController;

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\StudentAssessmentController;

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

// Forgot password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email');

// Reset password
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update');

// Dashboard
Route::get('/home', [DashboardController::class, 'index'])->name('home');

// Profile (Module 1)
Route::get('/profile', [DashboardController::class, 'editProfile'])->name('profile.edit');
Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');


/*
|--------------------------------------------------------------------------
| MODULE 1 – ADMIN (Teachers/Students)
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

Route::post('/administrator/teachers/{id}/toggle-status',
    [AdministratorController::class, 'toggleStatus']
)->name('administrator.teachers.toggleStatus');

// Students (READ)
Route::get('/administrator/studentslist', [AdministratorController::class, 'showStudentsList'])
    ->name('administrator.students.index');

Route::get('/administrator/students/{id}/edit', [AdministratorController::class, 'editStudent'])
    ->name('administrator.students.edit');

Route::put('/administrator/students/{id}', [AdministratorController::class, 'updateStudent'])
    ->name('administrator.students.update');

Route::post('/administrator/students/{id}/toggle-status', [AdministratorController::class, 'toggleStudentStatus'])
    ->name('administrator.students.toggleStatus');


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
| Module 3 – Student Content
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/student/module3',
        [ManageContentController::class, 'studentCourses']
    )->name('student.module3.courses');

    Route::get('/student/module3/course/{course}',
        [ManageContentController::class, 'studentContents']
    )->name('student.module3.contents');

});


/*
|--------------------------------------------------------------------------
| MODULE 4 – ASSESSMENTS (NEW)
|--------------------------------------------------------------------------
*/

// optional: bila user pergi /module4 terus bawa ke overview instructor
Route::get('/module4', function () {
    return redirect()->route('module4.instructorAssessments.overview');
})->name('module4.redirect');


// INSTRUCTOR ROUTES - Assessment Management
Route::prefix('module4/instructor')
    ->name('module4.instructorAssessments.')
    ->group(function () {

        Route::get('/assessments', [AssessmentController::class, 'overview'])
            ->name('overview');

        Route::get('/courses/{course}/assessments', [AssessmentController::class, 'index'])
            ->name('index');

        Route::get('/courses/{course}/assessments/create', [AssessmentController::class, 'create'])
            ->name('create');

        Route::post('/courses/{course}/assessments', [AssessmentController::class, 'store'])
            ->name('store');

        Route::get('/courses/{course}/assessments/{assessment}/edit', [AssessmentController::class, 'edit'])
            ->name('edit');

        Route::put('/courses/{course}/assessments/{assessment}', [AssessmentController::class, 'update'])
            ->name('update');

        Route::delete('/courses/{course}/assessments/{assessment}', [AssessmentController::class, 'destroy'])
            ->name('destroy');

        // Question management
        Route::post('/courses/{course}/assessments/{assessment}/questions',
            [AssessmentController::class, 'addQuestion']
        )->name('questions.store');

        Route::delete('/courses/{course}/assessments/{assessment}/questions/{question}',
            [AssessmentController::class, 'deleteQuestion']
        )->name('questions.destroy');

        // Results & grading
        Route::get('/courses/{course}/assessments/{assessment}/results',
            [AssessmentController::class, 'viewResults']
        )->name('results');

        Route::put('/courses/{course}/assessments/{assessment}/submissions/{submission}/feedback',
            [AssessmentController::class, 'updateFeedback']
        )->name('feedback.update');
    });


// STUDENT ROUTES - Taking Assessments
Route::prefix('module4/student')
    ->name('module4.studentAssessments.')
    ->group(function () {

        Route::get('/assessments', [StudentAssessmentController::class, 'overview'])
            ->name('overview');

        Route::get('/courses/{course}/assessments', [StudentAssessmentController::class, 'index'])
            ->name('index');

        Route::get('/courses/{course}/assessments/{assessment}',
            [StudentAssessmentController::class, 'show']
        )->name('show');

        // Quiz/exam flow
        Route::post('/courses/{course}/assessments/{assessment}/start',
            [StudentAssessmentController::class, 'startAttempt']
        )->name('start');

        Route::get('/courses/{course}/assessments/{assessment}/attempts/{attempt}',
            [StudentAssessmentController::class, 'takeQuiz']
        )->name('take');

        Route::post('/courses/{course}/assessments/{assessment}/attempts/{attempt}/submit',
            [StudentAssessmentController::class, 'submitQuiz']
        )->name('submit');

        Route::get('/courses/{course}/assessments/{assessment}/attempts/{attempt}/review',
            [StudentAssessmentController::class, 'reviewAttempt']
        )->name('review');

        // Assignment submission
        Route::post('/courses/{course}/assessments/{assessment}/submit',
            [StudentAssessmentController::class, 'submitAssignment']
        )->name('assignment.submit');

        Route::delete('/courses/{course}/assessments/{assessment}/delete',
            [StudentAssessmentController::class, 'deleteAssignment']
        )->name('assignment.delete');

        // Autosave (AJAX)
        Route::post('/courses/{course}/assessments/{assessment}/attempts/{attempt}/autosave',
            [StudentAssessmentController::class, 'autosave']
        )->name('autosave');
    });
