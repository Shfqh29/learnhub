@extends('layouts.learnhub')

@section('content')
@php
    $form = request('form', 1);
@endphp

{{-- HEADER --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">
        Learning Materials – Form {{ $form }}
    </h1>
    <p class="text-sm text-gray-500">
        Select a subject to begin learning
    </p>
</div>

{{-- COURSE LIST --}}
<div class="space-y-4">

@forelse($courses as $course)

    <div class="bg-white rounded-xl shadow border p-5 flex justify-between items-center">

        <div>
            <h3 class="font-semibold text-lg text-gray-800">
                {{ $course->title }}
            </h3>
            <p class="text-sm text-gray-500">
                {{ $course->description }}
            </p>
        </div>

        <a href="{{ route('student.content.index', $course->id) }}?form={{ $form }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
            View Content
        </a>
    </div>

@empty
    <p class="text-gray-500 italic">
        No courses available for this form.
    </p>
@endforelse

</div>
@endsection
