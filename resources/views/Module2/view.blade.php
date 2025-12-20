@extends('Module1.teacher.layout')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- PAGE HEADER --}}
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Course Details</h1>

        <a href="{{ route('manage_courses.edit', $course->id) }}"
           class="px-5 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
            Edit Course
        </a>
    </div>

    

 <div class="mx-auto bg-white shadow-lg rounded-xl overflow-hidden" style="width: 380px;">

    {{-- IMAGE EXACT SIZE --}}
    <img src="{{ $course->image_url ? asset('storage/' . $course->image_url) : 'https://via.placeholder.com/400x200' }}"
         alt="{{ $course->title }}"
         style="width: 380px; height: 160px; object-fit: cover;">

    {{-- COURSE TITLE --}}
<div class="flex items-center text-gray-800 mb-6 text-xl">
    <span class="font-bold mr-2">Course Title:</span>
    <span class="text-black font-normal">{{ $course->title }}</span>
</div>

      {{-- COORDINATOR --}}
<div class="flex items-center text-gray-600 mb-6 text-lg">
    <span class="font-bold text-gray-700 mr-2">Coordinator:</span>
    <span class="text-black font-normal">
        {{ $course->teacher_id ? $course->teacher->name ?? 'Teacher' : 'Teacher' }}
    </span>
</div>

{{-- DIFFICULTY --}}
<div class="flex items-center mb-8">
    <p class="text-gray-700 text-md font-bold mr-4">
        Difficulty of this subject:
    </p>

    <div class="flex items-center gap-2 ml-1">
        @for($i = 1; $i <= 5; $i++)
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-6 h-6 {{ $i <= $course->difficulty ? 'text-yellow-400' : 'text-gray-300' }}"
                 fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.97 1.438 8.323L12 18.897 4.626 23.599l1.438-8.323-6.064-5.97 8.332-1.151z"/>
            </svg>
        @endfor
    </div>
</div>

        <div class="mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Course Description</h3>
            <p class="text-gray-700 leading-relaxed">
                {{ $course->description }}
            </p>
        </div>

    </div>
</div>


    {{-- BACK BUTTON --}}
    <div class="mt-6">
        <a href="{{ route('manage_courses.index') }}"
           class="px-5 py-2 bg-gray-300 text-gray-800 rounded-xl shadow hover:bg-gray-400 transition">
            Back to List
        </a>
    </div>

</div>

@endsection
