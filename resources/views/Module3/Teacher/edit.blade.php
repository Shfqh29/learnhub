@extends('Module1.teacher.layout')

@section('content')
@php
    $form = request('form');
@endphp

<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-xl shadow border">

        {{-- HEADER --}}
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                Edit Learning Content
            </h2>
        </div>

        {{-- FORM --}}
        <form method="POST"
              action="{{ route('content.update', $content->id) }}"
              enctype="multipart/form-data"
              class="px-6 py-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- WEEK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Week
                </label>
                <select name="title_id"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        required>
                    @foreach($titles as $title)
                        <option value="{{ $title->id }}"
                            {{ $content->title_id == $title->id ? 'selected' : '' }}>
                            {{ $title->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- NAME --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Content Name
                </label>
                <input type="text"
                       name="item_name"
                       value="{{ $content->item_name }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm"
                       required>
            </div>

            {{-- TYPE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Content Type
                </label>
                <select name="content_type"
                        id="contentType"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        required>
                    @foreach(['notes','pdf','image','video'] as $type)
                        <option value="{{ $type }}"
                            {{ $content->content_type === $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- DESCRIPTION / LINK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description / Video Link
                </label>
                <textarea name="description"
                          rows="3"
                          class="w-full border rounded-lg px-3 py-2 text-sm">{{ $content->description }}</textarea>
            </div>

            {{-- FILE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Replace File
                </label>
                <input type="file"
                       name="file"
                       id="fileInput"
                       class="w-full border rounded-lg px-3 py-2 text-sm">
                <p class="text-xs text-gray-500 mt-1">
                    Disabled for video content
                </p>
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2 pt-4 border-t">
                <a href="{{ route('content.index', $course->id) }}?form={{ $form }}"
                   class="px-4 py-2 text-sm bg-gray-400 text-white rounded">
                    Cancel
                </a>

                <button type="submit"
                        class="px-4 py-2 text-sm bg-green-600 text-white rounded">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('contentType').dispatchEvent(new Event('change'));
document.getElementById('contentType').addEventListener('change', function () {
    const fileInput = document.getElementById('fileInput');
    if (this.value === 'video') {
        fileInput.disabled = true;
        fileInput.value = '';
        fileInput.classList.add('opacity-50');
    } else {
        fileInput.disabled = false;
        fileInput.classList.remove('opacity-50');
    }
});
</script>
@endsection
