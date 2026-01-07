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
                Add Learning Content
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Upload files or paste video links
            </p>
        </div>

        {{-- FORM --}}
        <form method="POST"
              action="{{ route('content.store', $course->id) }}"
              enctype="multipart/form-data"
              class="px-6 py-6 space-y-4">
            @csrf

            {{-- IMPORTANT: PASS FORM --}}
            <input type="hidden" name="form" value="{{ $course->form }}">

            {{-- WEEK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Week
                </label>
                <select name="title_id"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        required>
                    <option value="">-- Select Week --</option>
                    @foreach($titles as $title)
                        <option value="{{ $title->id }}"
                            {{ isset($selectedTitle) && $selectedTitle == $title->id ? 'selected' : '' }}>
                            {{ $title->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- CONTENT NAME --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Content Name
                </label>
                <input type="text"
                       name="item_name"
                       class="w-full border rounded-lg px-3 py-2 text-sm"
                       placeholder="Lecture Video / Notes / Worksheet"
                       required>
            </div>

            {{-- CONTENT TYPE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Content Type
                </label>
                <select name="content_type"
                        id="contentType"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        required>
                    <option value="">-- Select Type --</option>
                    <option value="notes">Notes</option>
                    <option value="pdf">PDF</option>
                    <option value="image">Image</option>
                    <option value="video">Video (YouTube / Drive link)</option>
                </select>
            </div>

            {{-- DESCRIPTION / LINK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description / Video Link
                </label>
                <textarea name="description"
                          rows="3"
                          class="w-full border rounded-lg px-3 py-2 text-sm"
                          placeholder="For video: paste YouTube / Google Drive link here"></textarea>
                <p class="text-xs text-gray-500 mt-1">
                    Example: https://www.youtube.com/watch?v=xxxx
                </p>
            </div>

            {{-- FILE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Upload File
                </label>
                <input type="file"
                       name="file"
                       id="fileInput"
                       class="w-full border rounded-lg px-3 py-2 text-sm">
                <p class="text-xs text-gray-500 mt-1">
                    Disabled automatically for video content
                </p>
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2 pt-4 border-t">
                <a href="{{ route('content.index', $course->id) }}?form={{ $form }}"
                   class="px-4 py-2 text-sm rounded bg-gray-400 text-white hover:bg-gray-500">
                    Cancel
                </a>

                <button type="submit"
                        class="px-4 py-2 text-sm rounded bg-green-600 text-white hover:bg-green-700">
                    Save Content
                </button>
            </div>

        </form>
    </div>
</div>

{{-- SCRIPT --}}
<script>
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
