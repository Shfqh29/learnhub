@extends('layouts.learnhub')

@section('content')
    <h1 class="text-3xl font-bold text-black mb-6">Add Content - {{ $course->title }}</h1>

    @if($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('content.store', $course->id) }}" enctype="multipart/form-data"
          class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" class="w-full border rounded-lg px-4 py-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">File</label>
            <input type="file" name="file" class="w-full border rounded-lg px-4 py-2" required>
        </div>

        <div class="flex gap-3">
            <button class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Upload
            </button>

            <a href="{{ route('content.index', $course->id) }}"
               class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Back
            </a>
        </div>
    </form>
@endsection
