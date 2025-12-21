@extends('Module1.teacher.layout')

@section('content')

{{-- PAGE HEADER --}}
<div class="mb-10">
    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
        Manage Learning Content
    </h1>
    <p class="text-gray-500 mt-1">
        Select a course to manage its weekly learning materials
    </p>
</div>

{{-- EMPTY STATE --}}
@if($courses->isEmpty())
    <div class="bg-white rounded-2xl shadow-md p-12 text-center">
        <div class="text-6xl mb-4">📂</div>
        <h2 class="text-xl font-bold text-gray-700 mb-2">
            No courses available
        </h2>
        <p class="text-gray-500 max-w-md mx-auto">
            You don’t have any courses assigned yet.
            Once courses are available, they will appear here.
        </p>
    </div>
@else

{{-- COURSE GRID --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">

@foreach($courses as $course)
    <div
        class="bg-white rounded-2xl shadow-md hover:shadow-xl
               transition duration-300 flex flex-col overflow-hidden">

        {{-- COURSE HEADER --}}
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-5 text-white">
            <h2 class="font-bold text-lg leading-snug">
                {{ $course->title }}
            </h2>
            <p class="text-sm text-indigo-100 mt-1">
                Form {{ $course->form }}
            </p>
        </div>

        {{-- COURSE BODY --}}
        <div class="p-6 flex flex-col flex-grow">
            <p class="text-sm text-gray-600 leading-relaxed line-clamp-4">
                {{ $course->description }}
            </p>

            {{-- META INFO --}}
            <div class="flex items-center gap-4 mt-4 text-xs text-gray-500">
                <span class="flex items-center gap-1">
                    📘 <span>Learning Content</span>
                </span>
                <span class="flex items-center gap-1">
                    👩‍🏫 <span>Teacher</span>
                </span>
            </div>

            {{-- ACTION --}}
            <div class="mt-auto pt-6">
                <a href="{{ route('content.index', $course->id) }}"
                   class="block text-center w-full px-4 py-2.5
                          bg-blue-600 text-white rounded-lg
                          hover:bg-blue-700 transition font-semibold shadow">
                    Manage Content →
                </a>
            </div>
        </div>
    </div>
@endforeach

</div>
@endif

@endsection
