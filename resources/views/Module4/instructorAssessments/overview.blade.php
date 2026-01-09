@extends('Module1.teacher.layout')

@section('content')

<div class="max-w-7xl mx-auto py-10">

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Assessment</h1>

    {{-- Statistics --}}
    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-500 text-white rounded-lg p-6">
            <p class="text-sm">Total Courses</p>
            <p class="text-3xl font-bold">{{ $totalCourses }}</p>
        </div>
        <div class="bg-green-500 text-white rounded-lg p-6">
            <p class="text-sm">Total Assessments</p>
            <p class="text-3xl font-bold">{{ $totalAssessments }}</p>
        </div>
        <div class="bg-purple-500 text-white rounded-lg p-6">
            <p class="text-sm">Active Quizzes</p>
            <p class="text-3xl font-bold">{{ $activeQuizzes }}</p>
        </div>
        <div class="bg-orange-500 text-white rounded-lg p-6">
            <p class="text-sm">Submissions</p>
            <p class="text-3xl font-bold">{{ $totalSubmissions }}</p>
        </div>
    </div>

    {{-- Course List --}}
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-2xl font-bold mb-6">Select a Course</h2>
        
        @foreach($courses as $course)
            <div class="border rounded-lg p-6 mb-4 hover:shadow-lg cursor-pointer"
                 onclick="window.location='{{ route('module4.instructorAssessments.index', $course->id) }}'">
                <h3 class="text-xl font-bold">{{ $course->title }}</h3>
                <p class="text-gray-600">{{ $course->description }}</p>
                <p class="text-sm text-gray-500 mt-2">
                    {{ $course->assessments->count() }} assessment(s)
                </p>
            </div>
        @endforeach
    </div>

</div>

@endsection