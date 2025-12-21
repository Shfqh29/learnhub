@extends('Module1.teacher.layout')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    Form {{ $form }} — Select Course
</h1>

@if($courses->isEmpty())
    <p class="text-gray-500 italic">
        No courses available for this form.
    </p>
@endif

@foreach($courses as $course)
<div class="bg-white p-4 rounded shadow flex justify-between mb-3">
    <div>
        <h3 class="font-semibold">{{ $course->title }}</h3>
        <p class="text-sm text-gray-500">{{ $course->description }}</p>
    </div>

    {{-- IMPORTANT: PASS FORM --}}
    <a href="{{ route('content.index', $course->id) }}?form={{ $form }}"
       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Manage Content
    </a>
</div>
@endforeach

@endsection
