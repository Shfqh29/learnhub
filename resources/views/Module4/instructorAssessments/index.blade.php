@extends('layouts.learnhub')

@section('content')

<div class="max-w-7xl mx-auto py-10">

    {{-- PAGE TITLE --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $course->title }}</h1>
            <p class="text-gray-600 mt-1">Assessments</p>
        </div>
        <a href="{{ route('module4.instructorAssessments.create', $course->id) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all">
            + Create Assessment
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- ASSESSMENTS LIST --}}
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
        
        @if($assessments->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No assessments created yet.</p>
                <p class="text-gray-400 mt-2">Click "Create Assessment" to add your first assessment.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($assessments as $assessment)
                    <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-800">{{ $assessment->title }}</h3>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($assessment->type == 'quiz') bg-blue-100 text-blue-800
                                        @elseif($assessment->type == 'exam') bg-purple-100 text-purple-800
                                        @elseif($assessment->type == 'assignment') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($assessment->type) }}
                                    </span>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($assessment->status == 'published') bg-green-100 text-green-800
                                        @elseif($assessment->status == 'draft') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($assessment->status) }}
                                    </span>
                                </div>
                                <p class="text-gray-600 mb-3">{{ $assessment->description }}</p>
                                <div class="flex gap-6 text-sm text-gray-500">
                                    <span>📊 Total Marks: {{ $assessment->total_marks }}</span>
                                    @if($assessment->duration)
                                        <span>⏱️ Duration: {{ $assessment->duration }} mins</span>
                                    @endif
                                    <span>🔄 Attempts: {{ $assessment->attempts_allowed }}</span>
                                    @if($assessment->due_date)
                                        <span>📅 Due: {{ $assessment->due_date->format('M d, Y H:i') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex gap-2 ml-4">
                                <a href="{{ route('module4.instructorAssessments.edit', [$course->id, $assessment->id]) }}"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg transition-all">
                                    Edit
                                </a>
                                <a href="{{ route('module4.instructorAssessments.results', [$course->id, $assessment->id]) }}"
                                   class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition-all">
                                    Results
                                </a>
                                <form action="{{ route('module4.instructorAssessments.destroy', [$course->id, $assessment->id]) }}" 
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this assessment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition-all">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    {{-- BACK BUTTON --}}
    <div class="mt-6">
        <a href="{{ route('module4.instructorAssessments.overview') }}" 
           class="text-gray-600 hover:text-gray-900 font-medium">
            ← Back to Manage Assessment
        </a>
    </div>

</div>

@endsection