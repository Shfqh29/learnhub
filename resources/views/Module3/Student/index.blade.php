@extends('layouts.learnhub')

@section('content')
@php
    use Illuminate\Support\Str;
    $form = request('form');
@endphp

{{-- HEADER --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">
        {{ $course->title }}
    </h1>
    <p class="text-sm text-gray-500">
        Learning content by week
    </p>
</div>

{{-- WEEK LIST --}}
<div class="space-y-6">

@foreach($titles as $title)

<div class="bg-white rounded-xl shadow border">

    {{-- WEEK HEADER --}}
    <button onclick="toggleWeek({{ $title->id }})"
            class="w-full flex justify-between items-center px-6 py-4 bg-gray-50 rounded-t-xl hover:bg-gray-100 transition">

        <h3 class="font-semibold text-blue-700">
            {{ $title->title }}
        </h3>

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

        <div class="flex gap-4 p-4 border rounded-lg">

            <div class="text-2xl {{ $iconColor }}">
                <i class="bi {{ $icon }}"></i>
            </div>

            <div>
                {{-- TITLE --}}
                @if($item->content_type === 'video' && Str::startsWith($item->description, ['http://','https://']))
                    <a href="{{ $item->description }}"
                       target="_blank"
                       class="font-medium text-blue-600 hover:underline">
                        ▶ {{ $item->item_name }}
                    </a>
                @elseif($item->file_path)
                    <a href="{{ asset('storage/' . $item->file_path) }}"
                       target="_blank"
                       class="font-medium text-gray-800 hover:underline">
                        {{ $item->item_name }}
                    </a>
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

        @empty
            <p class="text-sm text-gray-500 italic">
                No content for this week yet.
            </p>
        @endforelse

    </div>
</div>

@endforeach
</div>

<script>
function toggleWeek(id) {
    document.getElementById('content-' + id).classList.toggle('hidden');
    document.getElementById('icon-' + id).classList.toggle('rotate-180');
}
</script>

@endsection
