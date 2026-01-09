@extends('Module1.student.layout')

@section('content')

<div class="max-w-5xl mx-auto py-10">

    {{-- PAGE TITLE --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $assessment->title }}</h1>
        <p class="text-gray-600 mt-1">{{ ucfirst($assessment->type) }}</p>
    </div>

    {{-- ASSESSMENT INFO CARD --}}
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100 mb-6">
        <div class="mb-6 pb-4 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Assessment Information</h2>
        </div>

        <div class="space-y-3 mb-6">
            <p class="text-gray-700"><span class="font-semibold">Description:</span> {{ $assessment->description }}</p>
            <p class="text-gray-700"><span class="font-semibold">Total Marks:</span> {{ $assessment->total_marks }}</p>
            @if($assessment->duration)
                <p class="text-gray-700"><span class="font-semibold">Duration:</span> {{ $assessment->duration }} minutes</p>
            @endif
            <p class="text-gray-700"><span class="font-semibold">Attempts Allowed:</span> {{ $assessment->attempts_allowed }}</p>
            @if($assessment->due_date)
                <p class="text-gray-700"><span class="font-semibold">Due Date:</span> {{ $assessment->due_date->format('M d, Y H:i') }}</p>
            @endif
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800 font-semibold">
                You have used {{ $attempts->count() }} out of {{ $assessment->attempts_allowed }} attempt(s)
            </p>
        </div>
    </div>

    {{-- PREVIOUS ATTEMPTS --}}
    @if($attempts->isNotEmpty())
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100 mb-6">
        <div class="mb-6 pb-4 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Your Attempts</h2>
        </div>

        <div class="space-y-4">
            @foreach($attempts as $attempt)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">Attempt #{{ $attempt->attempt_number }}</h3>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p>Status: 
                                    <span class="font-semibold
                                        @if($attempt->status == 'completed') text-green-600
                                        @else text-yellow-600
                                        @endif">
                                        {{ ucfirst($attempt->status) }}
                                    </span>
                                </p>
                                @if($attempt->completed_at)
                                    <p>Completed: {{ $attempt->completed_at->format('M d, Y H:i') }}</p>
                                @endif
                                @if($attempt->status == 'completed' && $assessment->show_scores)
                                    <p class="font-semibold text-blue-600">
                                        Score: {{ $attempt->marks_obtained }}/{{ $assessment->total_marks }}
                                        ({{ round(($attempt->marks_obtained / $assessment->total_marks) * 100, 2) }}%)
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($attempt->status == 'completed')
                            <a href="{{ route('module4.studentAssessments.review', [$assessment->course_id, $assessment->id, $attempt->id]) }}"
   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition-all">
    Review
</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- START ATTEMPT BUTTON --}}
    @if($assessment->isOpen() && $attempts->count() < $assessment->attempts_allowed)

<div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
    <div class="text-center">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Ready to Start?</h3>
        <p class="text-gray-600 mb-6">
            Make sure you have enough time to complete this assessment.
        </p>

        <form action="{{ route('module4.studentAssessments.start', [$assessment->course_id, $assessment->id]) }}"
              method="POST"
              onsubmit="return confirm('Are you ready to start this assessment? The timer will begin immediately.')">
            @csrf
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all text-lg">
                Start Assessment
            </button>
        </form>
    </div>
</div>

@else

<div class="bg-gray-100 border border-gray-300 rounded-xl p-6 text-center">
    <p class="text-gray-600 font-semibold">
        You have used all allowed attempts for this assessment.
    </p>
</div>

@endif


    {{-- BACK BUTTON --}}
    <div class="mt-6">
        <a href="{{ route('module4.studentAssessments.index', $assessment->course_id) }}" 
           class="text-gray-600 hover:text-gray-900 font-medium">
            ← Back to Assessments
        </a>
    </div>

</div>

@endsection