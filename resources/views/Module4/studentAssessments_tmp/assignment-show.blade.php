@extends('Module1.student.layout')

@section('content')

<div class="max-w-5xl mx-auto py-10">

    {{-- PAGE TITLE --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $assessment->title }}</h1>
        <p class="text-gray-600 mt-1">{{ ucfirst($assessment->type) }}</p>
    </div>

    {{-- ASSESSMENT INFO --}}
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100 mb-6">
        <h2 class="text-2xl font-bold mb-4">Assignment Details</h2>

        <p><b>Description:</b> {{ $assessment->description }}</p>
        <p><b>Total Marks:</b> {{ $assessment->total_marks }}</p>

        @if($assessment->due_date)
            <p>
                <b>Due Date:</b> {{ $assessment->due_date->format('M d, Y H:i') }}
                @if($assessment->due_date < now())
                    <span class="text-red-600">(Overdue)</span>
                @endif
            </p>
        @endif
    </div>

    {{-- ASSESSMENT CLOSED NOTICE --}}
@if($assessment->status === 'closed')
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <p class="text-red-800 font-semibold">
            ⛔ This assessment has been closed by the instructor.
        </p>
        <p class="text-red-700 text-sm mt-1">
            You can no longer submit or update your work.
        </p>
    </div>
@endif

    {{-- STATUS --}}
    @if($submission && $submission->status === 'graded')
    <div class="
    rounded-xl p-6 mt-4
    @if($submission->status === 'graded')
        bg-green-50 border border-green-200
    @else
        bg-blue-50 border border-blue-200
    @endif
">
       <h3 class="text-lg font-bold mb-3
    @if($submission->status === 'graded')
        text-green-800
    @else
        text-blue-800
    @endif
">
    @if($submission->status === 'graded')
        ✔ Your Submitted Work (Graded)
    @else
        ✓ Your Submitted Work (Submitted)
    @endif
</h3>


        @if($submission->submission_text)
            <div class="mb-4">
                <p class="font-semibold text-gray-700 mb-1">Written Submission:</p>
                <div class="bg-gray-50 p-3 rounded text-gray-800 whitespace-pre-wrap">
                    {{ $submission->submission_text }}
                </div>
            </div>
        @endif

        @if($submission->file_path)
            <div class="mb-4">
                <p class="font-semibold text-gray-700 mb-1">Uploaded File:</p>
                <a href="{{ Storage::url($submission->file_path) }}"
                   target="_blank"
                   class="text-blue-600 hover:underline">
                    📎 {{ basename($submission->file_path) }}
                </a>
            </div>
        @endif

        @if($submission->comments)
            <div>
                <p class="font-semibold text-gray-700 mb-1">Your Comments:</p>
                <div class="bg-gray-50 p-3 rounded text-gray-800">
                    {{ $submission->comments }}
                </div>
            </div>
        @endif
    </div>
@endif

    {{-- ========================= --}}
    {{-- UPDATE / SUBMIT FORM --}}
    {{-- ========================= --}}
    @if($assessment->status !== 'closed' && (!$submission || $submission->status !== 'graded'))
    <div class="bg-white shadow p-6 rounded mb-6">
        <h2 class="text-xl font-bold mb-4">
            {{ $submission ? 'Update Submission' : 'Submit Assignment' }}
        </h2>

        <form method="POST"
              action="{{ route('module4.studentAssessments.assignment.submit', [$assessment->course_id, $assessment->id]) }}"
              enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="font-semibold">Written Submission</label>
                <textarea name="submission_text" rows="6"
                          class="w-full border rounded p-2">{{ $submission->submission_text ?? '' }}</textarea>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Upload File</label>
                <input type="file" name="file" class="w-full border p-2 rounded">

                @if($submission && $submission->file_path)
                    <p class="mt-2 text-sm">
                        Current file:
                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="text-blue-600">
                            {{ basename($submission->file_path) }}
                        </a>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
    Uploading a new file will replace the previous one.
</p>
                @endif
            </div>

            <div class="mb-4">
                <label class="font-semibold">Comments</label>
                <textarea name="comments" rows="3"
                          class="w-full border rounded p-2">{{ $submission->comments ?? '' }}</textarea>
            </div>

            <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded">
                {{ $submission ? 'Update Submission' : 'Submit Assignment' }}
            </button>
        </form>
    </div>
    @endif

    {{-- ========================= --}}
    {{-- DELETE FORM (SEPARATE) --}}
    {{-- ========================= --}}
    @if($submission && $submission->status !== 'graded' && $assessment->status !== 'closed')
    <form method="POST"
          action="{{ route('module4.studentAssessments.assignment.delete', [$assessment->course_id, $assessment->id]) }}"
          onsubmit="return confirm('Delete this submission?');">
        @csrf
        @method('DELETE')

        <button type="submit"
                class="bg-red-600 text-white px-6 py-2 rounded">
            Delete Submission
        </button>
    </form>
    @endif

    {{-- BACK --}}
    <div class="mt-6">
        <a href="{{ route('module4.studentAssessments.index', $assessment->course_id) }}"
           class="text-blue-600">
            ← Back to Assessments
        </a>
    </div>

</div>

@endsection
