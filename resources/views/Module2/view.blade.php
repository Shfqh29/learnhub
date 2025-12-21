@extends('Module1.teacher.layout')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- PAGE HEADER --}}
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Course Details</h1>

        <a href="{{ route('module2.edit', $course->id) }}"
           class="px-5 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
            Edit Course
        </a>
    </div>

    

 <div class="mx-auto bg-white shadow-lg rounded-xl overflow-hidden" style="width: 380px;">

    {{-- IMAGE EXACT SIZE --}}
    <img src="{{ $course->image_url ? asset('storage/' . $course->image_url) : 'https://via.placeholder.com/400x200' }}"
         alt="{{ $course->title }}"
         style="width: 380px; height: 160px; object-fit: cover;">

{{-- STATUS --}}
<div class="flex justify-center mb-2 mt-2">
    <span class="px-3 py-1 text-sm font-semibold rounded 
        {{ $course->status_course == 'PENDING APPROVAL' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800' }}">
        {{ $course->status_course }}
    </span>
</div>


   {{-- TITLE --}}
<div class="text-xl font-bold text-gray-800 text-center mb-3">
    {{ $course->title }}
</div>

{{-- TEACHER --}}
<div class="text-gray-600 text-sm text-center mb-4">
    <span class="font-medium">Teacher :</span>
    {{ $course->teacher_id ? $course->teacher->name ?? 'Teacher' : 'Teacher' }}
</div>

{{-- DIFFICULTY --}}
<div class="mb-6 text-center"> 
    <p class="text-gray-700 text-sm font-medium mb-2">Difficulty of this subject:</p>
    <div class="flex justify-center gap-2">
        @for($i = 1; $i <= 5; $i++)
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-6 h-6 {{ $i <= $course->difficulty ? 'text-yellow-400' : 'text-gray-300' }}"
                 fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.97 1.438 8.323L12 18.897 4.626 23.599l1.438-8.323-6.064-5.97 8.332-1.151z"/>
            </svg>
        @endfor
    </div>
</div>

{{-- DESCRIPTION --}}
<div class="mb-6 text-justify text-gray-700 leading-relaxed">
    {{ $course->description }}
</div>


    </div>
</div>


    {{-- BACK BUTTON --}}
    <div class="mt-6">
        <a href="{{ route('module2.index') }}"
           class="px-5 py-2 bg-gray-300 text-gray-800 rounded-xl shadow hover:bg-gray-400 transition">
            Back to List
        </a>
    </div>

</div>

@endsection
