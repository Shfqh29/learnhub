@extends('layouts.learnhub')

@section('content')
    <h1 class="text-3xl font-bold text-black mb-6">Module 3 - Select Course</h1>

    @if($courses->count() == 0)
        <div class="p-4 rounded-lg bg-yellow-100 text-yellow-900">
            No courses found. Please add a course in Module 2 first.
        </div>
    @else
        <div class="space-y-4">
            @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow p-5 flex items-center justify-between">
                    <div>
                        <div class="text-xl font-semibold text-gray-800">{{ $course->title }}</div>
                        <div class="text-sm text-gray-500">{{ $course->description ?? 'No description' }}</div>
                    </div>

                    <a href="{{ route('content.index', $course->id) }}"
                       class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Manage Content
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
