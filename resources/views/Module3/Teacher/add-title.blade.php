@extends('layouts.learnhub')

@section('content')
@php
    $form = request('form');
@endphp

<div class="max-w-xl mx-auto">

    <div class="bg-white rounded-xl shadow border">

        {{-- HEADER --}}
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                Add Week – {{ $course->title }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Create a new learning week for this course
            </p>
        </div>

        {{-- FORM --}}
        <form method="POST"
              action="{{ route('title.store', $course->id) }}"
              class="px-6 py-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Week Title
                </label>
                <input type="text"
                       name="title"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200"
                       placeholder="Week 1 – Introduction"
                       required>
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2 pt-4 border-t">
                <a href="{{ route('content.index', $course->id) }}?form={{ $form }}"
                   class="px-4 py-2 text-sm rounded bg-gray-400 text-white hover:bg-gray-500">
                    Cancel
                </a>

                <button type="submit"
                        class="px-4 py-2 text-sm rounded bg-green-600 text-white hover:bg-green-700">
                    Save Week
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
