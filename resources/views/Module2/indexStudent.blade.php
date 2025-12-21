@extends('Module1.student.layout')

@section('content')



<div x-data="courseModal()">

    <div class="flex justify-between items-center mb-10">
        <h1 class="text-3xl font-bold text-black">Available Courses</h1>
    </div>

    {{-- COURSES GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-10 auto-rows-fr">

        {{-- MODAL --}}
<div 
    x-show="show"
    x-transition
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
>
    <div @click.away="show=false"
         class="bg-white w-full max-w-lg rounded-xl shadow-lg overflow-hidden"
    >

        {{-- IMAGE --}}
        <img :src="course.image"
             class="w-full h-48 object-contain bg-gray-100">

       


        {{-- DETAILS --}}
        <div class="p-6 space-y-4">

            {{-- TITLE --}}
            <h2 class="text-2xl font-bold text-gray-800 text-center"
                x-text="course.title">
            </h2>

            {{-- DESCRIPTION --}}
            <p class="text-gray-600 text-justify leading-relaxed"
               x-text="course.description">
            </p>

            {{-- TEACHER --}}
            <div class="text-sm text-gray-500">
                <strong>Teacher:</strong>
                <span x-text="course.teacher"></span>
            </div>

            {{-- DIFFICULTY --}}
            <div>
                <p class="text-sm font-medium text-gray-700 mb-1">
                    Difficulty Of This Subject:
                </p>

                <div class="flex gap-1">
                    <template x-for="i in 5">
                        <svg class="w-5 h-5"
                             :class="i <= course.difficulty ? 'text-yellow-400' : 'text-gray-300'"
                             fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.97 
                                     1.438 8.323L12 18.897 4.626 23.599
                                     l1.438-8.323-6.064-5.97 
                                     8.332-1.151z"/>
                        </svg>
                    </template>
                </div>
            </div>

            {{-- BACK BUTTON --}}
            <div class="flex justify-end pt-4">
                <button
                    @click="show = false"
                    class="px-5 py-2 rounded-lg bg-gray-600 text-white
                           hover:bg-gray-700 transition">
                    Back
                </button>
                    </div>

                </div>
            </div>
        </div>


        {{-- COURSE CARDS --}}
        @foreach($courses as $course)

@php
// Extract form number sahaja dari title
preg_match('/[1-5]/', $course->title, $matches);
$courseForm = $matches[0] ?? null;

// Student form dari DB (sudah nombor)
$normalizedStudentForm = $studentForm;
$normalizedCourseForm = $courseForm;

$isAccessible = $normalizedStudentForm && $normalizedCourseForm &&
    $normalizedStudentForm === $normalizedCourseForm;
@endphp

    <div
    class="
        relative bg-white rounded-xl shadow transition overflow-hidden flex flex-col
        {{ $isAccessible ? 'hover:shadow-lg cursor-pointer' : 'opacity-70 blur-[0.5px] cursor-not-allowed
' }}
    "
    @if($isAccessible)
        @click="openModal({
            id: {{ $course->id }},
            title: @js($course->title),
            description: @js($course->description),
           teacher: @js($course->coordinator ?? $course->teacher->name ?? 'Teacher'),
            difficulty: {{ $course->difficulty }},
            status: @js($course->status_course),
            image: @js($course->image_url ? asset('storage/'.$course->image_url) : 'https://via.placeholder.com/400x200')
        })"
    @endif
>


            {{-- IMAGE --}}
            <img src="{{ $course->image_url ? asset('storage/' . $course->image_url) : 'https://via.placeholder.com/400x200' }}"
                 alt="{{ $course->title }}"
                 class="w-full h-40 object-cover">

            @if(!$isAccessible)
    <div class="absolute top-2 left-1/2 -translate-x-1/2 bg-red-600 text-white text-xs px-3 py-1 rounded-full shadow z-10 text-center">
    Not Accessible by you
</div>

@endif
           

            {{-- STATUS: hanya tunjuk kalau bukan APPROVED --}}
            @if($course->status_course !== 'APPROVED')
            <div class="flex justify-center mb-2 mt-2">
                <span class="px-3 py-1 text-sm font-semibold rounded
                    @if($course->status_course == 'REJECTED') bg-red-200 text-red-800 @endif
                    @if($course->status_course == 'PENDING APPROVAL') bg-yellow-200 text-yellow-800 @endif
                ">
                    {{ $course->status_course }}
                </span>
            </div>
            @endif

        {{-- CARD BODY --}}
<div class="p-4 flex flex-col items-center">

    {{-- TITLE --}}
    <h3 class="text-lg font-semibold text-gray-800 text-center leading-snug">
        {{ $course->title }}
    </h3>

    {{-- TEACHER --}}
   <p class="text-sm text-gray-600 text-center mt-1 mb-1">
    <strong>Teacher:</strong> {{ $course->coordinator ?? $course->teacher->name ?? 'Teacher' }}
</p>


                {{-- DIFFICULTY --}}
                <div class="text-center">
                    <p class="text-gray-700 text-sm font-medium mb-1">
                        Difficulty Of This Subject:
                    </p>
                    <div class="flex justify-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 {{ $i <= $course->difficulty ? 'text-yellow-400' : 'text-gray-300' }}"
                                 fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.97 1.438 8.323L12 18.897 4.626 23.599l1.438-8.323-6.064-5.97 8.332-1.151z"/>
                            </svg>
                        @endfor
                    </div>
                </div>

            </div>

        </div>
        @endforeach

    </div>
</div>

<script>
function courseModal() {
    return {
        show: false,
        course: {
            id: null,
            title: '',
            description: '',
            teacher: '',
            difficulty: 0,
            status: '',
            image: ''
        },
        openModal(data) {
            this.course = data;
            this.show = true;
        }
    }
}
</script>

@endsection
