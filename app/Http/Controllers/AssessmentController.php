<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Course;
use App\Models\ManageCourse;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Submission;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{

    // INSTRUCTOR: Display assessment overview (all courses)
    public function overview()
    {
        $courses = ManageCourse::with('assessments')->get();

        $totalCourses = $courses->count();
        $totalAssessments = Assessment::count();
        $activeQuizzes = Assessment::whereIn('type', ['quiz', 'exam'])
            ->where('status', 'published')
            ->count();
        $totalSubmissions = Submission::count();

        return view('module4.instructorAssessments.overview', compact(
            'courses',
            'totalCourses',
            'totalAssessments',
            'activeQuizzes',
            'totalSubmissions'
        ));
    }

    // Display all assessments for a course
    public function index($courseId)
    {
        $course = ManageCourse::findOrFail($courseId);
        $assessments = Assessment::where('course_id', $courseId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('module4.instructorAssessments.index', compact('course', 'assessments'));
    }

    // Show create assessment form
    public function create($courseId)
    {
        $course = ManageCourse::findOrFail($courseId);
        return view('module4.instructorAssessments.create', compact('course'));
    }

    // Store new assessment
    public function store(Request $request, $courseId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:quiz,exam,assignment,lab_exercise',
            'duration' => 'nullable|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1',
            'show_scores' => 'boolean',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['course_id'] = $courseId;
        $validated['show_scores'] = $request->has('show_scores');

        $assessment = Assessment::create($validated);

        return redirect()->route('module4.instructorAssessments.edit', [$courseId, $assessment->id])
            ->with('success', 'Assessment created successfully!');
    }

    // Show edit assessment form
    public function edit($courseId, $assessmentId)
    {
        $course = ManageCourse::findOrFail($courseId);
        $assessment = Assessment::with('questions.options')->findOrFail($assessmentId);

        return view('module4.instructorAssessments.edit', compact('course', 'assessment'));
    }

    // Update assessment
    public function update(Request $request, $courseId, $assessmentId)
    {
        $assessment = Assessment::findOrFail($assessmentId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:quiz,exam,assignment,lab_exercise',
            'duration' => 'nullable|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1',
            'show_scores' => 'boolean',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,published,closed'
        ]);

        $validated['show_scores'] = $request->has('show_scores');
        $assessment->update($validated);

        return redirect()->route('module4.instructorAssessments.edit', [$courseId, $assessmentId])
            ->with('success', 'Assessment updated successfully!');
    }

    // Delete assessment
    public function destroy($courseId, $assessmentId)
    {
        $assessment = Assessment::findOrFail($assessmentId);
        $assessment->delete();

        return redirect()->route('module4.instructorAssessments.index', $courseId)
            ->with('success', 'Assessment deleted successfully!');
    }

    // Add question to quiz/exam
    public function addQuestion(Request $request, $courseId, $assessmentId)
{
    $baseRules = [
        'question_text' => 'required|string',
        'question_type' => 'required|in:multiple_choice,true_false,short_answer,long_answer',
        'marks' => 'required|integer|min:1',
    ];

    if (in_array($request->question_type, ['multiple_choice', 'true_false'])) {
        $baseRules['options'] = 'required|array|min:2';
        $baseRules['options.*.text'] = 'required|string';
        $baseRules['correct_answer'] = 'required|integer';
    }

    $validated = $request->validate($baseRules);

    $question = Question::create([
        'assessment_id' => $assessmentId,
        'question_text' => $validated['question_text'],
        'question_type' => $validated['question_type'],
        'marks' => $validated['marks'],
        'order' => Question::where('assessment_id', $assessmentId)->count() + 1,
    ]);

    if (in_array($validated['question_type'], ['multiple_choice', 'true_false'])) {
        foreach ($request->options as $index => $option) {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $option['text'],
                'is_correct' => $index == $request->correct_answer,
                'order' => $index + 1,
            ]);
        }
    }

    $assessment = Assessment::findOrFail($assessmentId);
    $assessment->total_marks = $assessment->questions()->sum('marks');
    $assessment->save();

    return back()->with('success', 'Question added successfully!');
}

// Delete question
public function deleteQuestion($courseId, $assessmentId, $questionId)
{
    $question = Question::findOrFail($questionId);
    $question->delete();

    // Recalculate total marks
    $assessment = Assessment::findOrFail($assessmentId);
    $assessment->total_marks = $assessment->questions()->sum('marks');
    $assessment->save();

    return back()->with('success', 'Question deleted successfully!');
}



    // Edit feedback for submission
    public function updateFeedback(Request $request, $courseId, $assessmentId, $submissionId)
    {
        $validated = $request->validate([
            'marks_obtained' => 'required|integer|min:0',
            'feedback' => 'nullable|string'
        ]);

        $submission = Submission::findOrFail($submissionId);
        $submission->update([
            'marks_obtained' => $validated['marks_obtained'],
            'feedback' => $validated['feedback'],
            'status' => 'graded'
        ]);

        return back()->with('success', 'Feedback updated successfully!');
    }

    // View assessment results (Instructor)
public function viewResults($courseId, $assessmentId)
{
    $assessment = Assessment::with([
        'attempts.user',
        'submissions.user'
    ])->findOrFail($assessmentId);

    return view(
        'module4.instructorAssessments.results',
        compact('assessment')
    );
}

}
