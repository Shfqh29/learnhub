<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageAuthenticationController;
use App\Http\Controllers\ManageCourseController;
use App\Http\Controllers\ManageContentController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\StudentAssessmentController;


Route::get('/module1', [ManageAuthenticationController::class, 'index'])
    ->name('module1.index');

Route::resource('module2', ManageCourseController::class);

Route::get('/module3', [ManageContentController::class, 'index'])
    ->name('module3.index');

Route::get('/module4', function () {
    return redirect()->route('module4.instructorAssessments.overview');
})->name('module4.redirect');


Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


// INSTRUCTOR ROUTES - Assessment Management
Route::prefix('module4/instructor')
    ->name('module4.instructorAssessments.')
    ->group(function () {


        // Overview (all courses)
        Route::get('/assessments', [AssessmentController::class, 'overview'])
            ->name('overview');

        // Assessments by course
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
        Route::post(
            '/courses/{course}/assessments/{assessment}/questions',
            [AssessmentController::class, 'addQuestion']
        )
            ->name('questions.store');

        Route::delete(
            '/courses/{course}/assessments/{assessment}/questions/{question}',
            [AssessmentController::class, 'deleteQuestion']
        )
            ->name('questions.destroy');

        // Results & grading
        Route::get(
            '/courses/{course}/assessments/{assessment}/results',
            [AssessmentController::class, 'viewResults']
        )
            ->name('results');

        Route::put(
            '/courses/{course}/assessments/{assessment}/submissions/{submission}/feedback',
            [AssessmentController::class, 'updateFeedback']
        )
            ->name('feedback.update');
    });


// STUDENT ROUTES - Taking Assessments
Route::prefix('module4/student')
    ->name('module4.studentAssessments.')
    ->group(function () {


        // Overview (all enrolled courses)
        // For students
        Route::get('/assessments', [StudentAssessmentController::class, 'overview'])
            ->name('overview');


        // Assessments by course
        Route::get('/courses/{course}/assessments', [StudentAssessmentController::class, 'index'])
            ->name('index');

        Route::get(
            '/courses/{course}/assessments/{assessment}',
            [StudentAssessmentController::class, 'show']
        )
            ->name('show');

        // Quiz / exam flow
        Route::post(
            '/courses/{course}/assessments/{assessment}/start',
            [StudentAssessmentController::class, 'startAttempt']
        )
            ->name('start');

        Route::get(
            '/courses/{course}/assessments/{assessment}/attempts/{attempt}',
            [StudentAssessmentController::class, 'takeQuiz']
        )
            ->name('take');

        Route::post(
            '/courses/{course}/assessments/{assessment}/attempts/{attempt}/submit',
            [StudentAssessmentController::class, 'submitQuiz']
        )
            ->name('submit');

        Route::get(
            '/courses/{course}/assessments/{assessment}/attempts/{attempt}/review',
            [StudentAssessmentController::class, 'reviewAttempt']
        )
            ->name('review');

        // Assignment submission
        Route::post(
            '/courses/{course}/assessments/{assessment}/submit',
            [StudentAssessmentController::class, 'submitAssignment']
        )
            ->name('assignment.submit');

        // DELETE assignment submission (STUDENT)
        Route::delete(
            '/courses/{course}/assessments/{assessment}/delete',
            [StudentAssessmentController::class, 'deleteAssignment']
        )
            ->name('assignment.delete');

        // Autosave (AJAX)
        Route::post(
            '/courses/{course}/assessments/{assessment}/attempts/{attempt}/autosave',
            [StudentAssessmentController::class, 'autosave']
        )
            ->name('autosave');
    });
