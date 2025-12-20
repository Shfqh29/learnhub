@extends('layouts.learnhub')

@section('content')

{{-- PAGE HEADER --}}
<div class="flex justify-between items-center mb-10">
    <h1 class="text-3xl font-bold text-black">Course Approval</h1>
</div>

{{-- SUCCESS MESSAGE (FLOATING) --}}
@if(session('success'))
<div id="flash-message"
     class="fixed top-5 left-1/2 transform -translate-x-1/2
            bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg text-center transition-opacity duration-500">
    {{ session('success') }}
</div>

<script>
setTimeout(() => {
    const flash = document.getElementById('flash-message');
    if(flash){
        flash.style.opacity = '0';
        setTimeout(() => flash.remove(), 500);
    }
}, 3000);
</script>
@endif

{{-- GRID --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8 auto-rows-fr">

@forelse($courses as $course)

   {{-- COURSE CARD --}}
<div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col min-h-[250px]"
     data-bs-toggle="modal"
     data-bs-target="#courseModal{{ $course->id }}">

    {{-- IMAGE --}}
    <img src="{{ $course->image_url ? asset('storage/'.$course->image_url) : 'https://via.placeholder.com/400x200' }}"
         class="w-full max-h-48 object-cover rounded-lg mb-3"> {{-- Gambar lebih besar --}}

    {{-- STATUS BADGE --}}
    <div class="flex justify-center py-2 bg-gray-50">
        @if($course->status === 'approved')
            <span class="px-4 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                APPROVED
            </span>
        @elseif($course->status === 'rejected')
            <span class="px-4 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">
                REJECTED
            </span>
        @else
            <span class="px-4 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">
                PENDING APPROVAL
            </span>
        @endif
    </div>

    {{-- CARD BODY --}}
    <div class="p-4 flex flex-col flex-grow"> {{-- Kurangkan padding sedikit --}}
        <h3 class="text-lg font-semibold text-gray-800 mb-1">
            {{ $course->title }}
        </h3>

        <p class="text-gray-500 text-sm mb-2">
            Teacher: {{ $course->coordinator ?? 'N/A' }}
        </p>

        {{-- DIFFICULTY --}}
        <div class="mt-auto pt-4">
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
</div>

{{-- MODAL --}}
<div class="modal fade" id="courseModal{{ $course->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered flex items-center min-h-screen">
        <div class="modal-content rounded-xl shadow-lg max-w-2xl w-full max-h-[80vh] overflow-y-auto mx-4">

            <div class="modal-body p-5 flex flex-col gap-4 relative">

         {{-- CLOSE BUTTON TOP RIGHT --}}
<button type="button"
        class="absolute top-5 right-1 bg-red-600 hover:bg-red-700 text-white p-2 rounded"
        id="backBtn{{ $course->id }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>

                {{-- IMAGE --}}
                <img src="{{ $course->image_url ? asset('storage/'.$course->image_url) : 'https://via.placeholder.com/400x200' }}"
                     class="w-full max-h-48 object-cover rounded-lg mb-3">

                {{-- TITLE --}}
                <h3 class="text-xl font-bold text-gray-800 text-center mb-3">
                    {{ $course->title }}
                </h3>

                {{-- STATUS BADGE --}}
                <div class="flex justify-center mb-3">
                    @if($course->status === 'approved')
                        <span class="px-4 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                            APPROVED
                        </span>
                    @elseif($course->status === 'rejected')
                        <span class="px-4 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">
                            REJECTED
                        </span>
                    @else
                        <span class="px-4 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">
                            PENDING APPROVAL
                        </span>
                    @endif
                </div>

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

                {{-- ACTION BUTTONS --}}
                @if($course->status === 'pending')
                    <div class="flex gap-4 w-full mt-auto">
                        <form action="{{ route('module2.approve', $course->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg">
                                Approve
                            </button>
                        </form>

                        <form action="{{ route('module2.reject', $course->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg">
                                Reject
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<script>
    
document.addEventListener('DOMContentLoaded', function () {
    @foreach($courses as $course)
        const modalEl{{ $course->id }} = document.getElementById('courseModal{{ $course->id }}');
        const modalInstance{{ $course->id }} = new bootstrap.Modal(modalEl{{ $course->id }}, {
            backdrop: false,  // jangan block body scroll
            keyboard: true
        });

        // Remove `modal-open` class from body setiap kali modal dibuka
        modalEl{{ $course->id }}.addEventListener('show.bs.modal', function () {
            document.body.classList.remove('modal-open');
        });

        // Button close
        document.getElementById('backBtn{{ $course->id }}').addEventListener('click', function () {
            modalInstance{{ $course->id }}.hide();
        });
    @endforeach
});


</script>

@empty
    <p class="col-span-3 text-center text-gray-500">
        No courses pending approval.
    </p>
@endforelse

</div>

@endsection
