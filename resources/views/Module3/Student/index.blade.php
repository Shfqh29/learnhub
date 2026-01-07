@extends('Module1.student.layout')

@section('content')
@php
    $studentForm = auth()->user()->form;
@endphp

{{-- DASHBOARD HEADER --}}
<div class="mb-10">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                My Learning Content
            </h1>
            <p class="text-gray-600 mt-1">
                Access learning materials for <span class="font-semibold">Form {{ $studentForm }}</span>
            </p>
        </div>

        {{-- FORM BADGE --}}
        <span class="px-4 py-2 rounded-full text-sm font-semibold
                     bg-indigo-100 text-indigo-700">
            Form {{ $studentForm }}
        </span>
    </div>
</div>

{{-- EMPTY STATE --}}
@if($courses->isEmpty())
    <div class="bg-white rounded-2xl shadow-md p-12 text-center">
        <div class="text-6xl mb-4">📘</div>
        <h2 class="text-xl font-bold text-gray-700 mb-2">
            No learning content yet
        </h2>
        <p class="text-gray-500 max-w-md mx-auto">
            Your teachers haven’t published any learning materials for your form.
            Please check back later.
        </p>
    </div>
@else

{{-- COURSE SECTION --}}
<div class="space-y-10">

    {{-- SECTION TITLE --}}
    <div class="flex items-center gap-3">
        <div class="h-8 w-1 bg-indigo-600 rounded-full"></div>
        <h2 class="text-xl font-bold text-gray-800">
            Available Courses
        </h2>
    </div>

    {{-- COURSE GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">

    @foreach($courses as $course)
        <div
            class="bg-white rounded-2xl shadow-md hover:shadow-xl
                   transition duration-300 flex flex-col overflow-hidden">

            {{-- COURSE HEADER --}}
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-5 text-white">
                <h3 class="font-bold text-lg leading-tight">
                    {{ $course->title }}
                </h3>
            </div>

            {{-- COURSE BODY --}}
            <div class="p-6 flex flex-col flex-grow">
                <p class="text-sm text-gray-600 leading-relaxed line-clamp-4">
                    {{ $course->description }}
                </p>

                {{-- META INFO --}}
                <div class="flex items-center gap-4 mt-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        📚 <span>Learning Module</span>
                    </span>
                    <span class="flex items-center gap-1">
                        🎓 <span>Form {{ $studentForm }}</span>
                    </span>
                </div>

                {{-- ACTION --}}
                <div class="mt-auto pt-6">
                    <a href="{{ route('student.module3.contents', $course->id) }}"
                       class="block text-center w-full px-4 py-2.5
                              bg-indigo-600 text-white rounded-lg
                              hover:bg-indigo-700 transition font-semibold">
                        Open Course
                    </a>
                </div>
            </div>
        </div>
    @endforeach

    </div>
</div>
@endif

@endsection
