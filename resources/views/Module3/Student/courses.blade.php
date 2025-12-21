@extends('Module1.student.layout')

@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-800">
        Learning Content
    </h1>
    <p class="text-gray-500 mt-1">
        Available courses for <span class="font-semibold">Form {{ $studentForm }}</span>
    </p>
</div>

{{-- EMPTY STATE --}}
@if($courses->isEmpty())
    <div class="bg-white rounded-xl shadow p-10 text-center">
        <div class="text-4xl mb-3">📚</div>
        <h2 class="text-lg font-semibold text-gray-700 mb-1">
            No courses available
        </h2>
        <p class="text-sm text-gray-500">
            Learning materials for your form will appear here once they are published.
        </p>
    </div>
@else

{{-- COURSE GRID --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

@foreach($courses as $course)
    <div
        class="bg-white rounded-xl shadow hover:shadow-xl transition duration-300
               flex flex-col overflow-hidden">

        {{-- TOP BAR --}}
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-4 text-white">
            <h2 class="font-bold text-lg leading-snug">
                {{ $course->title }}
            </h2>
        </div>

        {{-- BODY --}}
        <div class="p-6 flex flex-col flex-grow">
            <p class="text-sm text-gray-600 line-clamp-3">
                {{ $course->description }}
            </p>

            {{-- FOOTER --}}
            <div class="mt-auto pt-6 flex justify-between items-center">
                <span class="text-xs text-gray-400 uppercase tracking-wide">
                    Form {{ $studentForm }}
                </span>

                <a href="{{ route('student.module3.contents', $course->id) }}"
                   class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg
                          hover:bg-blue-700 transition shadow">
                    View Content →
                </a>
            </div>
        </div>
    </div>
@endforeach

</div>
@endif

@endsection
