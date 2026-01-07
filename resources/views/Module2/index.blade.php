@extends('Module1.teacher.layout')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="flex justify-between items-center mb-10">

        <h1 class="text-3xl font-bold text-black">Manage Courses</h1>

        <div class="flex items-center space-x-4">

            {{-- SEARCH --}}
            <div class="relative">
                <input type="text" placeholder="Search Courses..."
                       class="px-5 py-2.5 pl-12 w-72 border border-gray-300 rounded-xl shadow-sm
                              focus:ring-2 focus:ring-blue-400 focus:outline-none text-gray-700">

                <svg class="w-5 h-5 absolute left-4 top-3 text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
            </div>

        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show"
             class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-500
                    text-white px-8 py-3 rounded-xl shadow-lg text-lg"
             x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
    @endif

    {{-- ADD NEW COURSE BUTTON --}}
    <div class="mb-8">
        <a href="{{ route('module2.create') }}"
           class="bg-blue-600 text-white px-6 py-2.5 rounded-xl shadow
                  hover:bg-blue-700 transition font-medium">
            + Add New Courses
        </a>
    </div>

    {{-- COURSES GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-10 auto-rows-fr">
        @foreach($courses as $course)
            @php
                preg_match('/Form\s*([1-5])/i', $course->title, $matches);
                $courseForm = $matches[1] ?? null;

                $teacherAccess = match($teacher) {
                    'Ahmad Saiful' => [1,2,3],
                    'Nur Nadia' => [4,5],
                    default => []
                };

                $isAllowed = $courseForm && in_array($courseForm, $teacherAccess);
            @endphp

            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col min-h-[400px] {{ !$isAllowed ? 'opacity-40 pointer-events-none' : '' }}">

                {{-- IMAGE --}}
                <img src="{{ $course->image_url ? asset('storage/' . $course->image_url) : 'https://via.placeholder.com/400x200' }}"
                     alt="{{ $course->title }}"
                     class="w-full h-40 object-cover">

                @if(!$isAllowed)
                    <p class="text-red-600 font-bold text-center mt-2">Not Accessible by You</p>
                @endif

                {{-- STATUS --}}
                <div class="flex justify-center mb-1 mt-2">
                    <span class="px-3 py-1 text-sm font-semibold rounded
                        {{ $course->status_course == 'PENDING APPROVAL' ? 'bg-yellow-200 text-yellow-800' : '' }}
                        {{ $course->status_course == 'APPROVED' ? 'bg-green-200 text-green-800' : '' }}
                        {{ $course->status_course == 'REJECTED' ? 'bg-red-200 text-red-800' : '' }}">
                        {{ $course->status_course }}
                    </span>
                </div>

                {{-- CARD BODY --}}
                <div class="p-6 flex flex-col items-center text-center flex-grow">

                    {{-- TITLE --}}
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 text-center">
                        {{ $course->title }}
                    </h3>

                    {{-- TEACHER --}}
                    <div class="text-gray-500 text-sm mb-2">
                        <span class="font-medium">Coordinator :</span>
                        {{ $course->coordinator ?? 'Teacher' }}
                    </div>

                    {{-- DIFFICULTY --}}
                    <div class="mb-6 text-center">
                        <p class="text-gray-700 text-sm font-medium mb-1">Difficulty of this subject:</p>

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

                    {{-- BUTTONS --}}
                    <div class="flex justify-between items-center mt-auto pt-6 w-full">
                        @if($isAllowed)
                            <a href="{{ route('module2.show', $course->id) }}"
                               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                View
                            </a>

                            <form action="{{ route('module2.destroy', $course->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this course?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </form>
                        @else
                            <button class="px-4 py-2 bg-gray-400 text-white text-sm rounded-lg cursor-not-allowed">
                                Not Allowed
                            </button>

                            <button class="px-4 py-2 bg-gray-400 text-white text-sm rounded-lg cursor-not-allowed">
                                Not Allowed
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    </div>

@endsection

    </div>

@endsection
