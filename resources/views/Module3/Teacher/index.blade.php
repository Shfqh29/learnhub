@extends('layouts.learnhub')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-black">{{ $course->title }} - Contents</h1>

        <a href="{{ route('content.create', $course->id) }}"
           class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            + Add Content
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 font-semibold">Title</th>
                    <th class="p-4 font-semibold">File</th>
                    <th class="p-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contents as $content)
                    <tr class="border-t">
                        <td class="p-4">{{ $content->title }}</td>
                        <td class="p-4">
                            <a class="text-blue-600 hover:underline"
                               href="{{ asset('storage/'.$content->file_path) }}"
                               target="_blank">
                                View
                            </a>
                        </td>
                        <td class="p-4 flex gap-2">
                            <a href="{{ route('content.edit', $content->id) }}"
                               class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm">
                                Edit
                            </a>

                            <form action="{{ route('content.destroy', $content->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this content?');">
                                @csrf
                                @method('DELETE')
                                <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td class="p-4 text-gray-500" colspan="3">No content uploaded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
