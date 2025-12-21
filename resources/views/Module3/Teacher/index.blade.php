@extends('Module1.teacher.layout')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

{{-- PAGE HEADER --}}
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $course->title }}</h1>
        <p class="text-sm text-gray-500">Manage learning content by week</p>
    </div>

    <a href="{{ route('title.create', $course->id) }}"
       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm shadow">
        + Add Week
    </a>
</div>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div id="successAlert"
     class="mb-4 p-3 bg-green-100 text-green-700 rounded flex justify-between items-center shadow">
    <span>{{ session('success') }}</span>
    <button onclick="closeSuccess()" class="font-bold text-lg px-2">&times;</button>
</div>
@endif

{{-- WEEK LIST --}}
@foreach($titles as $index => $title)
<div class="bg-white border rounded-lg shadow mb-6">

    {{-- WEEK HEADER --}}
    <button type="button"
            onclick="toggleWeek({{ $index }})"
            class="w-full px-5 py-3 bg-gradient-to-r from-blue-50 to-white
                   border-b flex justify-between items-center
                   hover:bg-blue-100 transition">

        <div class="flex items-center gap-3">
            <i id="arrow-{{ $index }}"
               class="bi bi-chevron-down transition-transform duration-300"></i>

            <h3 class="font-semibold text-blue-700">
                {{ $title->title }}
            </h3>
        </div>

        <span class="text-xs text-gray-500">
            {{ $title->contents->count() }} items
        </span>
    </button>

    {{-- WEEK CONTENT (OPEN BY DEFAULT) --}}
    <div id="week-{{ $index }}"
         class="p-5 space-y-4">

        @forelse($title->contents as $item)

        @php
            if ($item->content_type === 'video' && Str::contains($item->description, ['youtube','youtu.be'])) {
                $icon = 'bi-youtube text-red-600';
            } elseif ($item->file_type === 'pdf') {
                $icon = 'bi-file-earmark-pdf-fill text-red-600';
            } elseif ($item->file_type === 'video') {
                $icon = 'bi-play-circle-fill text-purple-600';
            } elseif ($item->file_type === 'image') {
                $icon = 'bi-image-fill text-green-600';
            } else {
                $icon = 'bi-file-earmark-text-fill text-gray-600';
            }
        @endphp

        <div class="flex justify-between items-start border rounded-lg p-4
                    hover:bg-gray-50 transition duration-200">

            {{-- LEFT --}}
            <div class="flex gap-4">
                <i class="bi {{ $icon }} text-2xl"></i>

                <div>
                    {{-- CONTENT NAME --}}
                    @if($item->content_type === 'video' && Str::contains($item->description, ['youtube','youtu.be']))
                        <a href="{{ $item->description }}" target="_blank"
                           class="font-semibold text-blue-600 hover:underline">
                            {{ $item->item_name }}
                        </a>

                    @elseif($item->file_path)
                        <a href="{{ asset('storage/'.$item->file_path) }}"
                           target="_blank"
                           class="font-semibold text-gray-800 hover:text-blue-600 hover:underline">
                            {{ $item->item_name }}
                        </a>
                    @else
                        <span class="font-semibold text-gray-800">
                            {{ $item->item_name }}
                        </span>
                    @endif

                    {{-- DESCRIPTION --}}
                    @if($item->description && !Str::contains($item->description, ['youtube','youtu.be']))
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $item->description }}
                        </p>
                    @endif

                    <p class="text-xs text-gray-400 uppercase mt-1">
                        {{ $item->content_type }}
                    </p>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="flex gap-2">
                @if($item->file_path)
                    <button
                        onclick="openPreview('{{ asset('storage/'.$item->file_path) }}','{{ $item->file_type }}')"
                        class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded hover:bg-blue-200">
                        Preview
                    </button>
                @endif

                <a href="{{ route('content.edit', $item->id) }}"
                   class="px-3 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200">
                    Edit
                </a>

                <form action="{{ route('content.destroy', $item->id) }}"
                      method="POST"
                      class="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-3 py-1 text-xs bg-red-100 text-red-600 rounded hover:bg-red-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        @empty
            <p class="text-sm text-gray-500 italic">No content added yet.</p>
        @endforelse

        {{-- ADD CONTENT --}}
        <a href="{{ route('content.create', $course->id) }}?title={{ $title->id }}"
           class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
            + Add Content
        </a>
    </div>
</div>
@endforeach

{{-- DELETE CONFIRM MODAL --}}
<div id="deleteModal"
     class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-2">Delete Content</h2>
        <p class="text-sm text-gray-600 mb-5">
            Are you sure you want to delete this content?
            <br><span class="text-red-600 font-semibold">This action cannot be undone.</span>
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()"
                    class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-sm">
                Cancel
            </button>
            <button id="confirmDeleteBtn"
                    class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white text-sm">
                Yes, Delete
            </button>
        </div>
    </div>
</div>

{{-- PREVIEW MODAL (UNCHANGED) --}}
<div id="previewModal" class="fixed inset-0 bg-black/60 hidden z-50">
    <div id="previewWindow"
         class="absolute top-20 left-1/2 -translate-x-1/2
                bg-white rounded-lg shadow-xl
                w-full max-w-4xl h-[75vh] flex flex-col">

        <div id="previewHeader"
             class="cursor-move px-4 py-3 bg-blue-600 text-white
                    flex justify-between items-center rounded-t-lg">
            <span class="font-semibold">File Preview</span>
            <button onclick="closePreview()" class="text-xl">&times;</button>
        </div>

        <div class="flex-1 p-4 overflow-auto">
            <iframe id="previewFrame" class="w-full h-full hidden"></iframe>
            <img id="previewImage" class="max-w-full mx-auto hidden rounded">
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
function toggleWeek(index) {
    document.getElementById(`week-${index}`).classList.toggle('hidden');
    document.getElementById(`arrow-${index}`).classList.toggle('rotate-180');
}

function closeSuccess() {
    document.getElementById('successAlert')?.remove();
}
setTimeout(closeSuccess, 3000);

let deleteForm = null;
document.querySelectorAll('.deleteForm').forEach(form => {
    form.addEventListener('submit', e => {
        e.preventDefault();
        deleteForm = form;
        document.getElementById('deleteModal').classList.remove('hidden');
    });
});
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
document.getElementById('confirmDeleteBtn').onclick = () => deleteForm?.submit();

function openPreview(url, type) {
    const modal = document.getElementById('previewModal');
    const iframe = document.getElementById('previewFrame');
    const image = document.getElementById('previewImage');

    iframe.classList.add('hidden');
    image.classList.add('hidden');
    iframe.src = '';
    image.src = '';

    if (type === 'pdf' || type === 'document') {
        iframe.src = url;
        iframe.classList.remove('hidden');
    } else if (type === 'image') {
        image.src = url;
        image.classList.remove('hidden');
    }
    modal.classList.remove('hidden');
}
function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}
</script>

@endsection
