@extends('layouts.learnhub')

@section('content')

@php
    if (!isset($user)) {
        $user = (object)['type' => 'student'];
    }
@endphp

{{-- PAGE HEADER --}}
<div class="flex justify-between items-center mb-10">
    <h1 class="text-3xl font-bold text-black">Available Courses</h1>

    {{-- SEARCH --}}
    <div class="relative">
        <input type="text" placeholder="Search Courses..."
               class="px-5 py-2.5 pl-12 w-72 border border-gray-300 rounded-xl shadow-sm 
                      focus:ring-2 focus:ring-blue-400 focus:outline-none text-gray-700"
               id="searchInput">

        <svg class="w-5 h-5 absolute left-4 top-3 text-gray-400" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
        </svg>
    </div>
</div>

{{-- COURSES GRID --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8 auto-rows-fr">

    @forelse($courses->where('status', 'approved') as $course)
    <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col min-h-[300px]"
         data-bs-toggle="modal" data-bs-target="#courseModal{{ $course->id }}">

        {{-- IMAGE --}}
        <img src="{{ $course->image_url ? asset('storage/' . $course->image_url) : 'https://via.placeholder.com/400x200' }}"
             alt="{{ $course->title }}"
             class="w-full h-40 object-cover">

       

      {{-- CARD BODY --}}
<div class="p-4 flex flex-col flex-grow"> {{-- Kurangkan padding dari p-5 → p-4 --}}
    <h3 class="text-xl font-semibold text-gray-800 mb-1">{{ $course->title }}</h3> {{-- mb-2 → mb-1 --}}

    <div class="text-gray-500 text-sm mb-1"> {{-- mb-2 → mb-1 --}}
        Teacher: {{ $course->coordinator ?? 'N/A' }}
    </div>

    {{-- DIFFICULTY --}}
    <div class="mb-3">  {{-- mb-6 → mb-3 --}}
        <p class="text-gray-700 text-sm font-medium mb-1">Difficulty:</p>
        <div class="flex items-center gap-1">
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


    {{-- MODAL DETAIL COURSE --}}
    <div class="modal fade" id="courseModal{{ $course->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered flex items-center min-h-screen">
        <div class="modal-content rounded-xl shadow-lg max-w-2xl w-full max-h-[80vh] overflow-y-auto mx-4">

            <div class="modal-body p-5 flex flex-col gap-4 relative">

                {{-- CLOSE BUTTON --}}
                <button type="button"
                        class="absolute top-5 right-1 bg-red-600 hover:bg-red-700 text-white p-2 rounded"
                        data-bs-dismiss="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- IMAGE --}}
                <img src="{{ $course->image_url ? asset('storage/' . $course->image_url) : 'https://via.placeholder.com/400x200' }}"
                     class="w-full max-h-48 object-cover rounded-lg mb-3">

                {{-- TITLE --}}
                <h3 class="text-xl font-bold text-gray-800 text-center mb-3">
                    {{ $course->title }}
                </h3>

                {{-- TEACHER --}}
                <div class="text-left mb-3">
                    <span class="font-bold text-gray-800">Teacher:</span>
                    <span class="text-black">{{ $course->coordinator ?? 'N/A' }}</span>
                </div>

                {{-- DIFFICULTY --}}
                <div class="mb-4">
                    <p class="text-gray-700 text-sm font-medium mb-1">Difficulty:</p>
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 {{ $i <= $course->difficulty ? 'text-yellow-400' : 'text-gray-300' }}"
                                 fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.97 1.438 8.323L12 18.897 4.626 23.599l1.438-8.323-6.064-5.97 8.332-1.151z"/>
                            </svg>
                        @endfor
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <div class="mb-5">
                    <p class="text-gray-700 text-sm font-medium mb-1">Description:</p>
                    <p class="text-gray-600 text-sm leading-relaxed text-justify">
                        {{ $course->description ?? 'No description available.' }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>


    @empty
        <p class="col-span-3 text-center text-gray-500">
            No approved courses available.
        </p>
    @endforelse
</div>

{{-- SEARCH SCRIPT --}}
<script>
document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const cards = document.querySelectorAll('.course-card');
    cards.forEach(card => {
        const title = card.querySelector('h3').innerText.toLowerCase();
        if(title.includes(query)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

@endsection
