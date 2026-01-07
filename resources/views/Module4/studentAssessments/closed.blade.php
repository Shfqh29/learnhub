@extends('layouts.learnhub')

@section('content')
<div class="max-w-4xl mx-auto py-16 text-center">

    <h1 class="text-3xl font-bold mb-4">{{ $assessment->title }}</h1>

    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <p class="text-red-800 font-semibold text-lg">
            🔒 This assessment is closed
        </p>

        <p class="mt-2 text-gray-700">
            You can no longer attempt this assessment.
        </p>
    </div>

    <div class="mt-6">
        <a href="{{ route('module4.studentAssessments.index', $assessment->course_id) }}"
           class="text-blue-600">
            ← Back to Assessments
        </a>
    </div>
</div>
@endsection
