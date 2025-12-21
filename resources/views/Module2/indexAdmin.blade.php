@extends('Module1.administrator.layout')

@section('content')

<div x-data="courseModal()">


    {{-- PAGE HEADER --}}
    <div class="flex justify-between items-center mb-10">
        <h1 class="text-3xl font-bold text-black">Course Approval
</h1>

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
     class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-8 py-4 rounded-xl shadow-xl text-lg"
     x-init="setTimeout(() => show = false, 3000)">
    {{ session('success') }}
</div>
    @endif

    {{-- COURSES GRID --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-10 auto-rows-fr">

{{-- MODAL --}}
<div 
    x-show="show"
    x-transition
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
>
   <div @click.away="show=false" class="bg-white w-full max-w-lg rounded-xl shadow-lg overflow-hidden relative"
>
    
   {{-- CLOSE BUTTON --}}
<button @click="show=false" 
        class="absolute top-3 right-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center text-lg">
    &times;
</button>


        {{-- IMAGE --}}
<img :src="course.image"
     class="w-full h-48 object-contain bg-gray-100">
{{-- STATUS --}}
<div class="flex justify-center mt-2">
    <span
    class="px-3 py-1 text-sm font-semibold rounded"
    :class="{
        'bg-yellow-200 text-yellow-800': course.status === 'PENDING APPROVAL',
        'bg-green-200 text-green-800': course.status === 'APPROVED',
        'bg-red-200 text-red-800': course.status === 'REJECTED'
    }"
    x-text="course.status">
</span>

</div>

<div class="p-6 space-y-3"> 
<h2 class="text-2xl font-bold text-gray-800 text-center"
    x-text="course.title">
</h2>
<p class="text-gray-600 text-justify leading-relaxed"
   x-text="course.description">
</p>

            <div class="text-sm text-gray-500">
                <strong>Teacher:</strong> <span x-text="course.teacher"></span>
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
                <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.97 1.438 8.323L12 18.897 4.626 23.599l1.438-8.323-6.064-5.97 8.332-1.151z"/>
            </svg>
        </template>
    </div>
</div>


           {{-- ACTION --}}
            <div class="flex justify-end gap-3 pt-4">

                                    {{-- REJECT --}}
            <form method="POST" :action="`/module2/${course.id}/reject`">
            @csrf
            @method('PUT')
            <button
                class="px-5 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 disabled:opacity-50"
                :disabled="course.status === 'APPROVED' || course.status === 'REJECTED'">
                Reject
            </button>
            </form>

            {{-- APPROVE --}}
            <form method="POST" :action="`/module2/${course.id}/approve`">
            @csrf
            @method('PUT')
            <button
                class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 disabled:opacity-50"
                :disabled="course.status === 'APPROVED' || course.status === 'REJECTED'">
                Approve
            </button>
            </form>



            </div>

        </div>
    </div>
</div>


       @foreach($courses as $course)

<div 
    class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col
 cursor-pointer"
  @click="openModal({
    id: {{ $course->id }},
    title: @js($course->title),
    description: @js($course->description),
    teacher: @js($course->coordinator ?? $course->teacher->name ?? 'Teacher'),
    difficulty: {{ $course->difficulty }},
    status: @js($course->status_course),
    image: @js($course->image_url ? asset('storage/'.$course->image_url) : 'https://via.placeholder.com/400x200')
})"

>                
                {{-- IMAGE --}}
                <img src="{{ $course->image_url ? asset('storage/' . $course->image_url) : 'https://via.placeholder.com/400x200' }}"
                     alt="{{ $course->title }}"
                     class="w-full h-40 object-cover">

                {{-- STATUS CENTER --}}
                <div class="flex justify-center mb-2 mt-2">
                   <span class="px-3 py-1 text-sm font-semibold rounded
    @if($course->status_course == 'PENDING APPROVAL') bg-yellow-200 text-yellow-800 @endif
    @if($course->status_course == 'APPROVED') bg-green-200 text-green-800 @endif
    @if($course->status_course == 'REJECTED') bg-red-200 text-red-800 @endif
">
    {{ $course->status_course }}
</span>

                </div>

             {{-- CARD BODY --}}
<div class="p-6 flex flex-col items-center">

    {{-- TITLE --}}
    <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center leading-snug">
        {{ $course->title }}
    </h3>

    {{-- TEACHER --}}
    <p class="text-sm text-gray-600 text-center mb-4">
        <span class="font-medium text-gray-700">Teacher :</span>
    {{ $course->coordinator ?? $course->teacher->name ?? 'Teacher' }}
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
