@extends('Module1.teacher.layout')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
<div id="flash-message"
     class="fixed top-5 left-1/2 transform -translate-x-1/2
            bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg text-center transition-opacity duration-500">
    {{ session('success') }}
</div>

<script>
    // Auto hide flash message after 3 seconds
    setTimeout(() => {
        const flash = document.getElementById('flash-message');
        if(flash){
            flash.style.opacity = '0'; // fade out
            setTimeout(() => flash.remove(), 500); // remove from DOM after fade
        }
    }, 3000);
</script>
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
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8 auto-rows-fr">

        @foreach($courses as $course)
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col min-h-[300px]">
            
            {{-- IMAGE --}}
            <img src="{{ $course->image_url ? asset('storage/' . $course->image_url) : 'https://via.placeholder.com/400x200' }}"
                 alt="{{ $course->title }}"
                 class="w-full h-40 object-cover">

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
            <div class="p-5 flex flex-col flex-grow">

                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $course->title }}</h3>

             <div class="text-gray-500 text-sm mb-2">
    Teacher {{ $course->coordinator ?? 'No Coordinator' }}
</div>

                {{-- DIFFICULTY --}}
                <div class="mb-6"> 
                    <p class="text-gray-700 text-sm font-medium mb-1">Difficulty of this subject:</p>

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

                {{-- BUTTONS --}}
                <div class="flex justify-between items-center mt-auto pt-6">
                    <a href="{{ route('module2.show', $course->id) }}"
                       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                        View
                    </a>
<form id="delete-form-{{ $course->id }}" action="{{ route('module2.destroy', $course->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="button" onclick="confirmDelete({{ $course->id }})"
            class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
        Delete
    </button>
</form>

<script>
function confirmDelete(courseId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This course will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'swal2-confirm-button',
            cancelButton: 'swal2-cancel-button'
        },
        buttonsStyling: false // kita disable default styling supaya class kita berfungsi
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + courseId).submit();
        }
    });
}
</script>

<style>
.swal2-confirm-button,
.swal2-cancel-button {
    width: 100px; /* boleh ubah ikut size yang kau nak */
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 0.5rem;
    border: none;
    cursor: pointer;
}

.swal2-confirm-button {
    background-color: #d33;
    color: #fff;
}

.swal2-cancel-button {
    background-color: #3085d6;
    color: #fff;
    margin-left: 10px; /* space antara button */
}

.swal2-actions {
    display: flex !important;
    justify-content: center !important;
    gap: 10px;
}
</style>

                </div>

            </div>

        </div>
        @endforeach

    </div>

@endsection
