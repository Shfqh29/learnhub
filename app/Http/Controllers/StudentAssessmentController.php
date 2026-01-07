<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Course;
use App\Models\Attempt;
use App\Models\Answer;
use App\Models\ManageCourse;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentAssessmentController extends Controller
{

    private function getStudentId(): int
    {
        return Auth::id() ?? 1; // fallback sementara (user id 1 mesti wujud dalam users table)
    }

    // STUDENT: Display assessment overview (all enrolled courses)
    public function overview()
    {
        $userId = $this->getStudentId();


        $courses = ManageCourse::with(['assessments' => function ($query) {
            $query->where('status', 'published');
        }])->get();

        $totalCourses = $courses->count();
        $totalAssessments = Assessment::where('status', 'published')->count();

        $completedQuizzes = Attempt::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $submittedAssignments = Submission::where('user_id', $userId)
            ->whereIn('status', ['submitted', 'graded'])
            ->count();

        return view('module4.studentAssessments.overview', compact(
            'courses',
            'totalCourses',
            'totalAssessments',
            'completedQuizzes',
            'submittedAssignments'
        ));
    }

    // View all assessments for a course
    public function index($courseId)
    {
        $course = ManageCourse::findOrFail($courseId);
        $assessments = Assessment::where('course_id', $courseId)
            ->whereIn('status', ['published', 'closed'])
            ->orderBy('due_date', 'asc')
            ->get();

        return view('module4.studentAssessments.index', compact('course', 'assessments'));
    }

    // View assessment details
    public function show($courseId, $assessmentId)
    {
        $assessment = Assessment::with('questions.options')->findOrFail($assessmentId);
        $userId = $this->getStudentId();
        $now = now();

        // =========================
        // QUIZ / EXAM ATTEMPTS
        // =========================
        $attempts = Attempt::where('assessment_id', $assessmentId)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $hasAttempt = $attempts->isNotEmpty();

        // =========================
        // ASSIGNMENT SUBMISSION
        // =========================
        $submission = Submission::where('assessment_id', $assessmentId)
            ->where('user_id', $userId)
            ->first();

        // ⏳ NOT STARTED YET
        if ($assessment->start_date && $now->lt($assessment->start_date)) {
            return view('module4.studentAssessments.not-open', compact('assessment'));
        }

        // 🚫 CLOSED / OVERDUE
        // ❗ ONLY BLOCK IF STUDENT NEVER SUBMITTED / NEVER ATTEMPT
        if (
            ($assessment->status === 'closed'
                || ($assessment->due_date && $now->gt($assessment->due_date)))
            && !$hasAttempt
            && !$submission
        ) {
            return view('module4.studentAssessments.closed', compact('assessment'));
        }

        // ======================
        // QUIZ / EXAM
        // ======================
        if ($assessment->isQuizType()) {
            return view('module4.studentAssessments.quiz-show', compact(
                'assessment',
                'attempts'
            ));
        }

        // ======================
        // ASSIGNMENT / LAB
        // ======================
        return view('module4.studentAssessments.assignment-show', compact(
            'assessment',
            'submission'
        ));
    }

    // Start quiz/exam attempt
    public function startAttempt($courseId, $assessmentId)
    {
        $assessment = Assessment::findOrFail($assessmentId);
        $userId = $this->getStudentId();

        // ⏳ Prevent starting quiz before start date
        if ($assessment->start_date && now()->lt($assessment->start_date)) {
            return redirect()->back()
                ->with('error', 'This assessment has not started yet.');
        }

        // 🚫 Prevent starting closed assessment
        if ($assessment->status === 'closed') {
            return redirect()->back()
                ->with('error', 'This assessment is already closed.');
        }

        // Check if student can attempt
        if (!$assessment->canStudentAttempt($userId)) {
            return redirect()->back()
                ->with('error', 'You have used all your attempts for this assessment.');
        }

        //Kalau refresh → masuk balik attempt sama
        if ($assessment->isQuizType()) {
            $existing = Attempt::where('assessment_id', $assessmentId)
                ->where('user_id', $userId)
                ->where('status', 'in_progress')
                ->first();

            if ($existing) {
                return redirect()->route(
                    'module4.studentAssessments.take',
                    [$courseId, $assessmentId, $existing->id]
                );
            }
        }

        // Create new attempt
        $attemptNumber = $assessment->getStudentAttempts($userId) + 1;
        $attempt = Attempt::create([
            'assessment_id' => $assessmentId,
            'user_id' => $userId,
            'attempt_number' => $attemptNumber,
            'started_at' => now(),
            'status' => 'in_progress'
        ]);

        return redirect()->route('module4.studentAssessments.take', [
            $courseId,
            $assessmentId,
            $attempt->id
        ]);
    }

    // Take quiz/exam
    public function takeQuiz($courseId, $assessmentId, $attemptId)
    {
        $assessment = Assessment::with('questions.options')->findOrFail($assessmentId);
        $attempt = Attempt::findOrFail($attemptId);

        // Check if attempt belongs to current user
        if ($attempt->user_id !== $this->getStudentId()) {
            abort(403);
        }

        // Check if already completed
        if ($attempt->status === 'completed') {
            return redirect()->route('module4.studentAssessments.show', [$courseId, $assessmentId])
                ->with('error', 'This attempt has already been completed.');
        }

        return view('module4.studentAssessments.take-quiz', compact('assessment', 'attempt'));
    }

    // Submit quiz/exam
    public function submitQuiz(Request $request, $courseId, $assessmentId, $attemptId)
    {
        $attempt = Attempt::findOrFail($attemptId);
        $assessment = Assessment::with('questions.options')->findOrFail($assessmentId);

        // Save answers
        foreach ($request->answers as $questionId => $answerData) {
            $question = $assessment->questions()->findOrFail($questionId);

            $answer = Answer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'question_option_id' => $answerData['option_id'] ?? null,
                'answer_text' => $answerData['text'] ?? null
            ]);

            // Auto-grade multiple choice and true/false
            if (in_array($question->question_type, ['multiple_choice', 'true_false'])) {
                $selectedOption = $question->options()->find($answerData['option_id']);
                if ($selectedOption && $selectedOption->is_correct) {
                    $answer->is_correct = true;
                    $answer->marks_obtained = $question->marks;
                    $answer->save();
                }
            }
        }

        // Complete attempt
        $attempt->completed_at = now();
        $attempt->status = 'completed';
        $attempt->calculateScore();

        return redirect()->route('module4.studentAssessments.show', [$courseId, $assessmentId])
            ->with('success', 'Assessment submitted successfully!');
    }

    // Submit assignment
    public function submitAssignment(Request $request, $courseId, $assessmentId)
    {
        $validated = $request->validate([
            'submission_text' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'comments' => 'nullable|string'
        ]);

        $userId = $this->getStudentId();

        // 🔐 LOCK BY DATE & STATUS
        $assessment = Assessment::findOrFail($assessmentId);
        $now = now();

        if ($assessment->status === 'closed') {
            return redirect()->back()
                ->with('error', 'This assessment is already closed.');
        }

        if ($assessment->due_date && $now->gt($assessment->due_date)) {
            return redirect()->back()
                ->with('error', 'Submission is no longer allowed. The due date has passed.');
        }

        $submission = Submission::where('assessment_id', $assessmentId)
            ->where('user_id', $userId)
            ->first();

        $filePath = $submission?->file_path;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        Submission::updateOrCreate(
            [
                'assessment_id' => $assessmentId,
                'user_id' => $userId
            ],
            [
                'submission_text' => $validated['submission_text'],
                'file_path' => $filePath,
                'comments' => $validated['comments'],
                'status' => 'submitted',
                'submitted_at' => now()
            ]
        );

        return redirect()->route('module4.studentAssessments.show', [$courseId, $assessmentId])
            ->with('success', 'Assignment submitted successfully!');
    }

    // DELETE assignment submission (student)
    public function deleteAssignment($courseId, $assessmentId)
    {
        $userId = $this->getStudentId();

        $submission = Submission::where('assessment_id', $assessmentId)
            ->where('user_id', $userId)
            ->where('status', '!=', 'graded') // SAFETY
            ->firstOrFail();

        // delete uploaded file if exists
        if ($submission->file_path) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $submission->delete();

        return redirect()
            ->route('module4.studentAssessments.show', [$courseId, $assessmentId])
            ->with('success', 'Submission deleted successfully.');
    }

    // Review completed attempt
    public function reviewAttempt($courseId, $assessmentId, $attemptId)
    {
        $assessment = Assessment::with('questions.options')->findOrFail($assessmentId);
        $attempt = Attempt::with('answers.question.options')->findOrFail($attemptId);

        // Check if attempt belongs to current user
        if ($attempt->user_id !== $this->getStudentId()) {
            abort(403);
        }

        return view('module4.studentAssessments.review-quiz', compact('assessment', 'attempt'));
    }

    // 🔄 AUTOSAVE ANSWERS (AJAX)
    public function autosave(Request $request, $courseId, $assessmentId, $attemptId)
    {
        $attempt = Attempt::where('id', $attemptId)
            ->where('user_id', $this->getStudentId())
            ->where('status', 'in_progress')
            ->firstOrFail();

        foreach ($request->answers ?? [] as $questionId => $answerData) {

            Answer::updateOrCreate(
                [
                    'attempt_id' => $attempt->id,
                    'question_id' => $questionId,
                ],
                [
                    'question_option_id' => $answerData['option_id'] ?? null,
                    'answer_text' => $answerData['text'] ?? null,
                ]
            );
        }

        return response()->json([
            'status' => 'saved',
            'time' => now()->toDateTimeString()
        ]);
    }
}
