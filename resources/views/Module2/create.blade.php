@extends('layouts.learnhub')

@section('content')

<div class="max-w-4xl mx-auto py-10">

    {{-- PAGE TITLE --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Add Courses</h1>

    {{-- SECTION CARD --}}
    <div class="bg-white shadow-2xl rounded-2xl p-10 border border-gray-100 w-full">
        {{-- card ikut content, tak stretch full height --}}

        {{-- SECTION TITLE BAR --}}
        <div class="mb-8 pb-3 border-b border-gray-200">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">
                Course Information
            </h2>
        </div>

        <form action="{{ route('manage_courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="bg-red-200 text-red-700 p-3 mb-5 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

            {{-- COURSE TITLE --}}
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Course Title</label>
                <input type="text" name="title"
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 
                              focus:border-blue-500 transition-all shadow-sm hover:shadow-md outline-none"
                       placeholder="Enter course title" required>
            </div>

            {{-- COURSE DESCRIPTION --}}
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Course Description</label>
                <textarea name="description" rows="4"
                          class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 
                                 focus:border-blue-500 transition-all shadow-sm hover:shadow-md outline-none"
                          placeholder="Write a description about this course" required></textarea>
            </div>

            {{-- COURSE IMAGE --}}
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Picture of the Course</label>
                <input type="file" name="image"
                       class="w-full border border-gray-300 bg-white p-3 rounded-xl shadow-sm 
                              hover:shadow-md cursor-pointer transition-all">
            </div>

{{-- DIFFICULTY OF COURSE --}}
<div class="mb-8">
    <label class="block text-gray-800 font-semibold mb-2">Difficulty of this Course</label>
    <div class="flex items-center space-x-2">
        @for ($i = 1; $i <= 5; $i++)
            <svg 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" viewBox="0 0 24 24" 
                stroke="currentColor"
                class="w-8 h-8 cursor-pointer text-gray-300 hover:text-yellow-400 transition-colors star"
                data-value="{{ $i }}"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.172c.969 0 1.371 1.24.588 1.81l-3.375 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.538 1.118l-3.375-2.454a1 1 0 00-1.175 0l-3.375 2.454c-.783.57-1.838-.197-1.538-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.046 9.393c-.783-.57-.38-1.81.588-1.81h4.172a1 1 0 00.95-.69l1.286-3.966z" />
            </svg>
        @endfor
    </div>
    <input type="hidden" name="difficulty" id="difficulty" value="0">
    <p class="text-gray-500 text-sm mt-1">1 = Easy, 5 = Hard</p>
</div>

<script>
    const stars = document.querySelectorAll('.star');
    const difficultyInput = document.getElementById('difficulty');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = star.getAttribute('data-value');
            difficultyInput.value = value;

            stars.forEach(s => {
                if(s.getAttribute('data-value') <= value){
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });
</script>

            {{-- COURSE COORDINATOR --}}
            <div class="mb-8">
                <label class="block text-gray-800 font-semibold mb-2">Course Coordinator</label>
                <input type="text" name="coordinator"
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 
                              focus:border-blue-500 transition-all shadow-sm hover:shadow-md outline-none"
                       placeholder="Enter teacher/coordinator name" required>
            </div>

            {{-- BUTTONS --}}
            <div class="flex items-center space-x-5">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-xl 
                               shadow-md hover:shadow-lg transition-all">
                    Save
                </button>

                <button type="reset"
                        class="bg-red-600 text-white font-semibold px-8 py-3 rounded-xl shadow-md 
                               hover:bg-red-700 hover:shadow-lg transition-all">
                    Reset
                </button>

                <a href="{{ route('manage_courses.index') }}"
                   class="text-gray-600 hover:text-gray-900 font-medium ml-4">
                    Back
                </a>
            </div>

        </form>
    </div>

</div>

@endsection