@extends('Module1.student.layout')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

{{-- COURSE HEADER --}}
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-800">
        {{ $course->title }}
    </h1>
    <p class="text-gray-600 mt-2 max-w-3xl">
        {{ $course->description }}
    </p>
</div>

{{-- SEARCH --}}
<div class="mb-6">
    <input
        id="searchInput"
        type="text"
        placeholder="Search lesson or material..."
        class="w-full px-5 py-3 border rounded-xl shadow-sm
               focus:ring-2 focus:ring-indigo-500 focus:outline-none">
</div>

{{-- PROGRESS --}}
<div class="mb-10">
    <div class="flex justify-between mb-2 text-sm text-gray-600">
        <span>Course Progress</span>
        <span id="progressText">0%</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-3">
        <div id="progressBar"
             class="bg-indigo-600 h-3 rounded-full transition-all"
             style="width: 0%"></div>
    </div>
</div>

{{-- WEEK LIST --}}
@foreach($titles as $index => $title)
<div class="mb-8 bg-white rounded-2xl shadow overflow-hidden">

    {{-- WEEK HEADER --}}
    <button
        type="button"
        onclick="toggleWeek({{ $index }})"
        class="w-full px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-600
               flex justify-between items-center text-white
               hover:from-indigo-700 hover:to-blue-700 transition">

        <div class="flex items-center gap-3">
            <i class="bi bi-folder2-open text-xl"></i>
            <div class="text-left">
                <h2 class="font-bold text-lg">{{ $title->title }}</h2>
                <p class="text-xs opacity-90">Click to expand / collapse</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-sm opacity-90">
                {{ $title->contents->count() }} items
            </span>
            <i id="arrow-{{ $index }}"
               class="bi bi-chevron-down text-2xl transition-transform"></i>
        </div>
    </button>

    {{-- WEEK CONTENT --}}
    <div id="week-{{ $index }}" class="p-6 space-y-4 bg-gray-50">

        @forelse($title->contents as $item)

        @php
            if ($item->content_type === 'video' && Str::contains($item->description, ['youtube','youtu.be'])) {
                $icon = 'bi-youtube text-red-600';
                $badge = 'Video';
            } elseif ($item->file_type === 'pdf') {
                $icon = 'bi-file-earmark-pdf-fill text-red-600';
                $badge = 'PDF';
            } elseif ($item->file_type === 'image') {
                $icon = 'bi-image-fill text-green-600';
                $badge = 'Image';
            } elseif ($item->file_type === 'video') {
                $icon = 'bi-play-circle-fill text-purple-600';
                $badge = 'Video File';
            } else {
                $icon = 'bi-file-earmark-text-fill text-blue-600';
                $badge = 'Notes';
            }
        @endphp

        <div
            class="lesson-item flex justify-between items-start gap-6
                   bg-white border rounded-xl p-5
                   hover:shadow-md transition"
            data-title="{{ strtolower($item->item_name) }} {{ strtolower($item->description) }}">

            {{-- LEFT --}}
            <div class="flex gap-4">
                {{-- ICON --}}
                <div class="flex-shrink-0">
                    <i class="bi {{ $icon }} text-3xl"></i>
                </div>

                {{-- INFO --}}
                <div>
                    {{-- TITLE --}}
                    @if($item->content_type === 'video' && Str::contains($item->description, ['youtube','youtu.be']))
                        <a href="{{ $item->description }}"
                           target="_blank"
                           class="font-semibold text-blue-600 hover:underline text-lg">
                            {{ $item->item_name }}
                        </a>
                    @elseif($item->file_path)
                        <a href="{{ asset('storage/'.$item->file_path) }}"
                           target="_blank"
                           class="font-semibold text-gray-800 hover:text-blue-600 hover:underline text-lg">
                            {{ $item->item_name }}
                        </a>
                    @else
                        <span class="font-semibold text-gray-800 text-lg">
                            {{ $item->item_name }}
                        </span>
                    @endif

                    {{-- DESCRIPTION --}}
                    @if($item->description && !Str::contains($item->description, ['youtube','youtu.be']))
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $item->description }}
                        </p>
                    @endif

                    {{-- TYPE BADGE --}}
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold
                                 rounded-full bg-gray-100 text-gray-700">
                        {{ $badge }}
                    </span>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="flex flex-col items-end gap-3">

                {{-- COMPLETED --}}
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox"
                           class="lesson-check"
                           data-id="lesson-{{ $item->id }}">
                    Completed
                </label>

                {{-- ACTIONS --}}
                <div class="flex gap-2">
                    @if($item->file_path)
                        <button
                            onclick="openPreview('{{ asset('storage/'.$item->file_path) }}','{{ $item->file_type }}')"
                            class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded hover:bg-blue-200">
                            Preview
                        </button>

                        <a href="{{ asset('storage/'.$item->file_path) }}"
                           download
                           class="px-3 py-1 text-xs bg-green-100 text-green-600 rounded hover:bg-green-200">
                            Download
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @empty
            <p class="text-sm text-gray-500 italic">No content added yet.</p>
        @endforelse
    </div>
</div>
@endforeach

{{-- PREVIEW MODAL --}}
<div id="previewModal" class="fixed inset-0 bg-black/60 hidden z-50">
    <div class="absolute top-20 left-1/2 -translate-x-1/2
                bg-white rounded-lg shadow-xl
                w-full max-w-4xl h-[75vh] flex flex-col">

        <div class="px-4 py-3 bg-indigo-600 text-white
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

{{-- SCRIPT --}}
<script>
/* TOGGLE WEEK */
function toggleWeek(index) {
    document.getElementById(`week-${index}`).classList.toggle('hidden');
    document.getElementById(`arrow-${index}`).classList.toggle('rotate-180');
}

/* SEARCH */
document.getElementById('searchInput').addEventListener('input', function () {
    const value = this.value.toLowerCase();
    document.querySelectorAll('.lesson-item').forEach(item => {
        item.style.display = item.dataset.title.includes(value) ? '' : 'none';
    });
});

/* PREVIEW */
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

/* PROGRESS */
const checks = document.querySelectorAll('.lesson-check');
checks.forEach(check => {
    const id = check.dataset.id;
    check.checked = localStorage.getItem(id) === 'true';
    check.addEventListener('change', () => {
        localStorage.setItem(id, check.checked);
        updateProgress();
    });
});
function updateProgress() {
    const total = checks.length;
    const completed = [...checks].filter(c => c.checked).length;
    const percent = total ? Math.round((completed / total) * 100) : 0;
    document.getElementById('progressBar').style.width = percent + '%';
    document.getElementById('progressText').innerText = percent + '%';
}
updateProgress();
</script>

@endsection
