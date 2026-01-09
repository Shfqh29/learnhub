@extends('Module1.teacher.layout')

@section('content')

<div class="max-w-7xl mx-auto py-10">

    {{-- PAGE TITLE --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $assessment->title }}</h1>
        <p class="text-gray-600 mt-1">Assessment Results</p>
    </div>

    {{-- ASSESSMENT INFO --}}
    <div class="bg-white shadow-2xl rounded-2xl p-6 border border-gray-100 mb-6">
        <div class="grid grid-cols-4 gap-6 text-center">
            <div>
                <p class="text-gray-600 text-sm">Type</p>
                <p class="text-2xl font-bold text-gray-800">{{ ucfirst($assessment->type) }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Total Marks</p>
                <p class="text-2xl font-bold text-blue-600">{{ $assessment->total_marks }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Total Submissions</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ $assessment->isQuizType() ? $assessment->attempts->count() : $assessment->submissions->count() }}
                </p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Status</p>
                <p class="text-2xl font-bold
                    @if($assessment->status == 'published') text-green-600
                    @elseif($assessment->status == 'draft') text-gray-600
                    @else text-red-600
                    @endif">
                    {{ ucfirst($assessment->status) }}
                </p>
            </div>
        </div>
    </div>

    {{-- QUIZ/EXAM RESULTS --}}
    @if($assessment->isQuizType())
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
        <div class="mb-6 pb-4 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Student Attempts</h2>
        </div>

        @if($assessment->attempts->isEmpty())
            <p class="text-gray-500 text-center py-8">No attempts submitted yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Attempt</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Score</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Percentage</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Completed At</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($assessment->attempts->sortByDesc('completed_at') as $attempt)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-800">{{ $attempt->user->name }}</td>
                                <td class="px-6 py-4 text-gray-600">#{{ $attempt->attempt_number }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-blue-600">
                                        {{ $attempt->marks_obtained }}/{{ $assessment->total_marks }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold
                                        @if(($attempt->marks_obtained / $assessment->total_marks) * 100 >= 80) text-green-600
                                        @elseif(($attempt->marks_obtained / $assessment->total_marks) * 100 >= 60) text-yellow-600
                                        @else text-red-600
                                        @endif">
                                        {{ round(($attempt->marks_obtained / $assessment->total_marks) * 100, 2) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($attempt->status == 'completed') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($attempt->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ASSIGNMENT RESULTS --}}
    @else
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
        <div class="mb-6 pb-4 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Student Submissions</h2>
        </div>

        @if($assessment->submissions->isEmpty())
            <p class="text-gray-500 text-center py-8">No submissions yet.</p>
        @else
            <div class="space-y-4">
                @foreach($assessment->submissions->sortByDesc('submitted_at') as $submission)
                    <div class="border border-gray-200 rounded-xl p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $submission->user->name }}</h3>
                                
                                <div class="space-y-1 text-sm text-gray-600 mb-3">
                                    <p>Status: 
                                        <span class="font-semibold
                                            @if($submission->status == 'graded') text-green-600
                                            @elseif($submission->status == 'submitted') text-blue-600
                                            @else text-yellow-600
                                            @endif">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    </p>
                                    @if($submission->submitted_at)
                                        <p>Submitted: {{ $submission->submitted_at->format('M d, Y H:i') }}</p>
                                    @endif
                                    @if($submission->file_path)
                                        <p>
                                            Attachment: 
                                            <a href="{{ Storage::url($submission->file_path) }}" 
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-800">
                                                📎 {{ basename($submission->file_path) }}
                                            </a>
                                        </p>
                                    @endif
                                </div>

                                @if($submission->submission_text)
                                    <div class="bg-gray-50 p-4 rounded-lg mb-3">
                                        <p class="font-semibold text-gray-800 mb-1">Submission:</p>
                                        <p class="text-gray-700 text-sm whitespace-pre-wrap line-clamp-3">{{ $submission->submission_text }}</p>
                                    </div>
                                @endif

                                @if($submission->comments)
                                    <div class="bg-blue-50 p-4 rounded-lg mb-3">
                                        <p class="font-semibold text-gray-800 mb-1">Student Comments:</p>
                                        <p class="text-gray-700 text-sm">{{ $submission->comments }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-6">
                                <button
    onclick="openGradingModal(this)"
    data-submission-id="{{ $submission->id }}"
    data-student="{{ $submission->user->name }}"
    data-marks="{{ $submission->marks_obtained ?? 0 }}"
    data-feedback="{{ $submission->feedback }}"
    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition-all">
    {{ $submission->status === 'graded' ? 'Edit Grade' : 'Grade' }}
</button>

                            </div>
                        </div>

                        @if($submission->status === 'graded')
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-600 text-sm">Score</p>
                                        <p class="text-2xl font-bold text-green-600">
                                            {{ $submission->marks_obtained }}/{{ $assessment->total_marks }}
                                        </p>
                                    </div>
                                    @if($submission->feedback)
                                        <div>
                                            <p class="text-gray-600 text-sm mb-1">Feedback</p>
                                            <p class="text-gray-700 text-sm bg-yellow-50 p-2 rounded">{{ $submission->feedback }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif

    {{-- BACK BUTTON --}}
    <div class="mt-6">
        <a href="{{ route('module4.instructorAssessments.index', $assessment->course_id) }}" 
           class="text-gray-600 hover:text-gray-900 font-medium">
            ← Back to Assessments
        </a>
    </div>

</div>

{{-- GRADING MODAL --}}
<div id="gradingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Grade Submission</h3>
        
        <form id="gradingForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <p class="text-gray-700 mb-4">Student: <span id="studentName" class="font-bold"></span></p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Marks Obtained</label>
                <input type="number" name="marks_obtained" id="marksObtained" min="0" max="{{ $assessment->total_marks }}" required
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                <p class="text-gray-500 text-sm mt-1">Out of {{ $assessment->total_marks }} marks</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Feedback</label>
                <textarea name="feedback" id="feedbackText" rows="4"
                          class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                          placeholder="Provide feedback to the student..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded-xl transition-all">
                    Save Grade
                </button>
                <button type="button" onclick="closeGradingModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-3 rounded-xl transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openGradingModal(button) {
    const modal = document.getElementById('gradingModal');
    const form = document.getElementById('gradingForm');

    const submissionId = button.dataset.submissionId;
    const studentName = button.dataset.student;
    const marks = button.dataset.marks;
    const feedback = button.dataset.feedback;

    form.action = `/module4/instructor/courses/{{ $assessment->course_id }}/assessments/{{ $assessment->id }}/submissions/${submissionId}/feedback`;

    document.getElementById('studentName').textContent = studentName;
    document.getElementById('marksObtained').value = marks || 0;
    document.getElementById('feedbackText').value = feedback || '';

    modal.classList.remove('hidden');
}

function closeGradingModal() {
    document.getElementById('gradingModal').classList.add('hidden');
}
</script>


@endsection