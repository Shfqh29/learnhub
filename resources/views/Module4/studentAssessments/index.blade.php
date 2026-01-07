@extends('layouts.learnhub')

@section('content')

<div class="max-w-7xl mx-auto py-10">

    {{-- PAGE TITLE --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ $course->title }}</h1>
        <p class="text-gray-600 mt-1">Assessments</p>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
            {{ session('error') }}
        </div>
    @endif

    {{-- ASSESSMENTS LIST --}}
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
        
        @if($assessments->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No assessments available yet.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($assessments as $assessment)
                    @php
                        $userId = Auth::id();
                        $isOverdue = $assessment->due_date && $assessment->due_date < now();
                        
                        if ($assessment->isQuizType()) {
                            $attempts = $assessment->attempts()->where('user_id', $userId)->get();
                            $isDone = $attempts->where('status', 'completed')->isNotEmpty();
                            $attemptsUsed = $attempts->count();
                            $canAttempt = $assessment->canStudentAttempt($userId);
                        } else {
                            $submission = $assessment->submissions()->where('user_id', $userId)->first();
                            $isDone = $submission && $submission->status !== 'pending';
                        }
                    @endphp

                    <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-800">{{ $assessment->title }}</h3>
                                    
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($assessment->type == 'quiz') bg-blue-100 text-blue-800
                                        @elseif($assessment->type == 'exam') bg-purple-100 text-purple-800
                                        @elseif($assessment->type == 'assignment') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($assessment->type) }}
                                    </span>

                                    @if($isDone)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            ✓ Completed
                                        </span>
                                    @elseif($isOverdue)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Overdue
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </div>

                                <p class="text-gray-600 mb-3">{{ $assessment->description }}</p>

                                <div class="flex gap-6 text-sm text-gray-500">
                                    <span>📊 {{ $assessment->total_marks }} marks</span>
                                    @if($assessment->duration)
                                        <span>⏱️ {{ $assessment->duration }} mins</span>
                                    @endif
                                    @if($assessment->due_date)
                                        <span>📅 Due: {{ $assessment->due_date->format('M d, Y H:i') }}</span>
                                    @endif
                                    @if($assessment->isQuizType())
                                        <span>🔄 Attempts: {{ $attemptsUsed }}/{{ $assessment->attempts_allowed }}</span>
                                    @endif
                                </div>

                                @if($assessment->isQuizType() && $isDone && $assessment->show_scores)
                                    @php
                                        $bestAttempt = $attempts->where('status', 'completed')->sortByDesc('marks_obtained')->first();
                                    @endphp
                                    @if($bestAttempt)
                                        <div class="mt-3 inline-block bg-blue-50 px-4 py-2 rounded-lg">
                                            <span class="text-blue-800 font-semibold">
                                                Best Score: {{ $bestAttempt->marks_obtained }}/{{ $assessment->total_marks }}
                                            </span>
                                        </div>
                                    @endif
                                @endif

                                @if($assessment->isAssignmentType() && isset($submission) && $submission->status === 'graded')
                                    <div class="mt-3 inline-block bg-green-50 px-4 py-2 rounded-lg">
                                        <span class="text-green-800 font-semibold">
                                            Score: {{ $submission->marks_obtained }}/{{ $assessment->total_marks }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-4">
                                <a href="{{ route('module4.studentAssessments.show', [$course->id, $assessment->id]) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition-all inline-block">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    {{-- BACK BUTTON --}}
    <div class="mt-6">
        <a href="{{ route('module4.studentAssessments.overview', $course->id) }}" class="text-gray-600 hover:text-gray-900 font-medium">
            ← Back to My Assessments
        </a>
    </div>

</div>

@endsection