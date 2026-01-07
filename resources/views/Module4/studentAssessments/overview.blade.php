@extends('layouts.learnhub')

@section('content')

<div class="max-w-7xl mx-auto py-10">

    {{-- PAGE TITLE --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">My Assessments</h1>
        <p class="text-gray-600">View and complete your assessments</p>
    </div>

    {{-- STUDENT STATISTICS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">My Courses</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalCourses }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Available Assessments</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalAssessments }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Completed Quizzes</p>
                    <p class="text-3xl font-bold mt-1">{{ $completedQuizzes }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Submitted Work</p>
                    <p class="text-3xl font-bold mt-1">{{ $submittedAssignments }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- MY COURSES & ASSESSMENTS --}}
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
        
        <div class="mb-6 pb-4 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">All Courses</h2>
            <p class="text-gray-600 mt-1">Select a course to view its assessments</p>
        </div>

        @if($courses->isEmpty())
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-gray-500 text-lg">You are not enrolled in any courses yet.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($courses as $course)
                    @php
                        $userId = Auth::id();
                        $publishedAssessments = $course->assessments;
                        
                        // Count different assessment types
                        $quizCount = $publishedAssessments->where('type', 'quiz')->count();
                        $examCount = $publishedAssessments->where('type', 'exam')->count();
                        $assignmentCount = $publishedAssessments->where('type', 'assignment')->count();
                        $labCount = $publishedAssessments->where('type', 'lab_exercise')->count();
                        
                        // Count completed/pending
                        $completedCount = 0;
                        $pendingCount = 0;
                        
                        foreach($publishedAssessments as $assessment) {
                            if($assessment->isQuizType()) {
                                $hasCompleted = $assessment->attempts()
                                    ->where('user_id', $userId)
                                    ->where('status', 'completed')
                                    ->exists();
                                if($hasCompleted) $completedCount++;
                                else $pendingCount++;
                            } else {
                                $hasSubmitted = $assessment->submissions()
                                    ->where('user_id', $userId)
                                    ->whereIn('status', ['submitted', 'graded'])
                                    ->exists();
                                if($hasSubmitted) $completedCount++;
                                else $pendingCount++;
                            }
                        }
                    @endphp

                    <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all cursor-pointer"
                         onclick="window.location='{{ route('module4.studentAssessments.index', $course->id) }}'">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    {{-- COURSE ICON --}}
                                    @if($course->image_url)
                                        <img src="{{ asset('storage/' . $course->image_url) }}" 
                                             alt="{{ $course->title }}" 
                                             class="w-16 h-16 rounded-lg object-cover">
                                    @else
                                        <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                            <span class="text-white text-2xl font-bold">{{ substr($course->title, 0, 1) }}</span>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-800">{{ $course->title }}</h3>
                                        <p class="text-gray-600 text-sm line-clamp-1">{{ $course->description }}</p>
                                    </div>
                                </div>

                                {{-- ASSESSMENT STATS --}}
                                <div class="flex gap-6 ml-20">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-600">{{ $completedCount }}</p>
                                        <p class="text-xs text-gray-500">Completed</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-yellow-600">{{ $pendingCount }}</p>
                                        <p class="text-xs text-gray-500">Pending</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-blue-600">{{ $quizCount }}</p>
                                        <p class="text-xs text-gray-500">Quizzes</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-purple-600">{{ $examCount }}</p>
                                        <p class="text-xs text-gray-500">Exams</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-orange-600">{{ $assignmentCount }}</p>
                                        <p class="text-xs text-gray-500">Assignments</p>
                                    </div>
                                </div>
                            </div>

                            {{-- ARROW BUTTON --}}
                            <div class="ml-6">
                                <a href="{{ route('module4.studentAssessments.index', $course->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all inline-flex items-center gap-2">
                                    View
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        {{-- UPCOMING/PENDING ASSESSMENTS --}}
                        @php
                            $upcomingAssessments = $publishedAssessments->filter(function($assessment) use ($userId) {
                                if($assessment->due_date && $assessment->due_date > now()) {
                                    if($assessment->isQuizType()) {
                                        return !$assessment->attempts()
                                            ->where('user_id', $userId)
                                            ->where('status', 'completed')
                                            ->exists();
                                    } else {
                                        return !$assessment->submissions()
                                            ->where('user_id', $userId)
                                            ->whereIn('status', ['submitted', 'graded'])
                                            ->exists();
                                    }
                                }
                                return false;
                            })->take(3);
                        @endphp

                        @if($upcomingAssessments->isNotEmpty())
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-2">Upcoming Assessments:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($upcomingAssessments as $assessment)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            @if($assessment->type == 'quiz') bg-blue-100 text-blue-800
                                            @elseif($assessment->type == 'exam') bg-purple-100 text-purple-800
                                            @elseif($assessment->type == 'assignment') bg-green-100 text-green-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ $assessment->title }}
                                            @if($assessment->due_date)
                                                <span class="ml-1 text-xs">• {{ $assessment->due_date->diffForHumans() }}</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</div>

@endsection