@extends('layouts.learnhub')

@section('content')
@php
    use Illuminate\Support\Str;
    $form = request('form');
@endphp

{{-- PAGE HEADER --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            {{ $course->title }}
        </h1>
        <p class="text-sm text-gray-500">
            Manage learning content by week
        </p>
    </div>

    <a href="{{ route('title.create', $course->id) }}?form={{ $form }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 shadow">
        <i class="bi bi-plus-circle"></i>
        Add Week
    </a>
</div>

{{-- SUCCESS MESSAGE (AUTO HIDE) --}}
@if(session('success'))
    <div id="successAlert"
         class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-200">
        {{ session('success') }}
    </div>
@endif

{{-- WEEK LIST --}}
<div class="space-y-6">

@foreach($titles as $title)

<div class="bg-white rounded-xl border shadow-sm">

    {{-- WEEK HEADER --}}
    <button onclick="toggleWeek({{ $title->id }})"
            class="w-full flex justify-between items-center px-6 py-4 bg-gray-50 rounded-t-xl hover:bg-gray-100 transition">

        <div>
            <h3 class="font-semibold text-blue-700">
                {{ $title->title }}
            </h3>
            <p class="text-xs text-gray-500">
                {{ $title->contents->count() }} materials
            </p>
        </div>

        <i id="icon-{{ $title->id }}"
           class="bi bi-chevron-down rotate-180 transition-transform"></i>
    </button>

    {{-- WEEK CONTENT --}}
    <div id="content-{{ $title->id }}" class="px-6 py-5 space-y-4">

        @forelse($title->contents as $item)

        @php
            $iconMap = [
                'pdf' => ['bi-file-earmark-pdf', 'text-red-600'],
                'video' => ['bi-play-circle', 'text-purple-600'],
                'image' => ['bi-image', 'text-green-600'],
                'document' => ['bi-file-earmark-text', 'text-blue-600'],
            ];
            [$icon, $iconColor] = $iconMap[$item->file_type] ?? $iconMap['document'];
        @endphp

        {{-- CONTENT ITEM --}}
        <div class="flex justify-between items-start gap-4 p-4 border rounded-lg hover:bg-gray-50">

            <div class="flex gap-4">
                <div class="text-2xl {{ $iconColor }}">
                    <i class="bi {{ $icon }}"></i>
                </div>

                <div>
                    {{-- CLICKABLE CONTENT NAME --}}
                    @if($item->content_type === 'video' && Str::startsWith($item->description, ['http://','https://']))
                        <a href="{{ $item->description }}"
                           target="_blank"
                           class="font-medium text-blue-600 hover:underline">
                            ▶ {{ $item->item_name }}
                        </a>
                    @elseif($item->file_path)
                        <button
                            onclick="openPreview(
                                '{{ asset('storage/' . $item->file_path) }}',
                                '{{ $item->file_type }}'
                            )"
                            class="font-medium text-gray-800 hover:text-blue-600 hover:underline text-left">
                            {{ $item->item_name }}
                        </button>
                    @else
                        <span class="font-medium text-gray-800">
                            {{ $item->item_name }}
                        </span>
                    @endif

                    {{-- DESCRIPTION --}}
                    @if($item->description && $item->content_type !== 'video')
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
                        onclick="openPreview(
                            '{{ asset('storage/' . $item->file_path) }}',
                            '{{ $item->file_type }}'
                        )"
                        class="px-3 py-1 text-xs rounded bg-blue-100 text-blue-600 hover:bg-blue-200">
                        Preview
                    </button>
                @endif

                <a href="{{ route('content.edit', $item->id) }}?form={{ $form }}"
                   class="px-3 py-1 text-xs rounded bg-gray-100 hover:bg-gray-200">
                    Edit
                </a>

                <button
                    onclick="openDeleteModal(
                        '{{ $item->item_name }}',
                        '{{ route('content.destroy', $item->id) }}'
                    )"
                    class="px-3 py-1 text-xs rounded bg-red-100 text-red-600 hover:bg-red-200">
                    Delete
                </button>
            </div>
        </div>

        @empty
            <p class="text-sm text-gray-500 italic">
                No learning materials yet.
            </p>
        @endforelse

        {{-- ADD CONTENT --}}
        <div class="pt-3">
            <a href="{{ route('content.create', $course->id) }}?title={{ $title->id }}&form={{ $form }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 shadow">
                <i class="bi bi-plus-circle"></i>
                Add Content
            </a>
        </div>
    </div>
</div>

@endforeach
</div>

{{-- PREVIEW MODAL (PDF / IMAGE ONLY) --}}
<div id="previewModal"
     class="fixed inset-0 bg-black/60 hidden z-50">

    <div id="previewWindow"
         class="absolute top-24 left-1/2 -translate-x-1/2
                bg-white rounded-lg shadow-xl
                w-full max-w-4xl h-[75vh] flex flex-col">

        {{-- HEADER (DRAG HANDLE) --}}
        <div id="previewHeader"
             class="cursor-move flex justify-between items-center px-4 py-3 bg-blue-600 text-white rounded-t-lg">
            <span class="font-semibold">File Preview</span>
            <button onclick="closePreview()" class="text-xl">&times;</button>
        </div>

        {{-- BODY --}}
        <div class="flex-1 p-4 overflow-auto">
            <iframe id="previewFrame" class="w-full h-full hidden"></iframe>
            <img id="previewImage" class="max-w-full mx-auto hidden rounded"/>
        </div>
    </div>
</div>

{{-- DELETE MODAL --}}
<div id="deleteModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="px-4 py-3 bg-red-600 text-white font-semibold flex justify-between">
            Confirm Delete
            <button onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="p-4">
            <p class="text-sm">Delete:</p>
            <p id="deleteItemName" class="font-semibold mt-1"></p>
        </div>
        <div class="flex justify-end gap-2 px-4 py-3 bg-gray-50">
            <button onclick="closeDeleteModal()"
                    class="px-4 py-2 text-sm bg-gray-400 text-white rounded">
                Cancel
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button class="px-4 py-2 text-sm bg-red-600 text-white rounded">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
/* AUTO HIDE SUCCESS */
setTimeout(() => {
    const alert = document.getElementById('successAlert');
    if (alert) {
        alert.classList.add('opacity-0');
        setTimeout(() => alert.remove(), 500);
    }
}, 3000);

/* TOGGLE WEEK */
function toggleWeek(id) {
    document.getElementById('content-' + id).classList.toggle('hidden');
    document.getElementById('icon-' + id).classList.toggle('rotate-180');
}

/* PREVIEW (PDF / IMAGE) */
function openPreview(url, type) {
    const modal = document.getElementById('previewModal');
    const iframe = document.getElementById('previewFrame');
    const image = document.getElementById('previewImage');

    iframe.classList.add('hidden');
    image.classList.add('hidden');
    iframe.src = image.src = '';

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

/* DRAGGABLE PREVIEW */
(() => {
    const modal = document.getElementById('previewWindow');
    const header = document.getElementById('previewHeader');
    let isDragging = false, offsetX = 0, offsetY = 0;

    header.addEventListener('mousedown', e => {
        isDragging = true;
        offsetX = e.clientX - modal.offsetLeft;
        offsetY = e.clientY - modal.offsetTop;
        document.body.style.userSelect = 'none';
    });

    document.addEventListener('mousemove', e => {
        if (!isDragging) return;
        modal.style.left = e.clientX - offsetX + 'px';
        modal.style.top = e.clientY - offsetY + 'px';
        modal.style.transform = 'none';
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
        document.body.style.userSelect = '';
    });
})();

/* DELETE */
function openDeleteModal(name, action) {
    document.getElementById('deleteItemName').innerText = name;
    document.getElementById('deleteForm').action = action;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
</script>

@endsection
