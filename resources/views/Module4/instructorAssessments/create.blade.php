@extends('layouts.learnhub')

@section('content')

<div class="max-w-4xl mx-auto py-10">

    {{-- PAGE TITLE --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Create New Assessment</h1>

    {{-- SECTION CARD --}}
    <div class="bg-white shadow-2xl rounded-2xl p-10 border border-gray-100">

        {{-- SECTION TITLE BAR --}}
        <div class="mb-8 pb-3 border-b border-gray-200">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">
                Assessment Information
            </h2>
        </div>

        <form action="{{ route('module4.instructorAssessments.store', $course->id) }}" method="POST">
            @csrf

            {{-- VALIDATION ERRORS --}}
            @if ($errors->any())
                <div class="bg-red-200 text-red-700 p-3 mb-5 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ASSESSMENT TITLE --}}
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Assessment Title</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 
                              focus:border-blue-500 transition-all shadow-sm hover:shadow-md outline-none"
                       placeholder="Enter assessment title" required>
            </div>

            {{-- ASSESSMENT DESCRIPTION --}}
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Description</label>
                <textarea name="description" rows="3"
                          class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 
                                 focus:border-blue-500 transition-all shadow-sm hover:shadow-md outline-none"
                          placeholder="Write instructions or description">{{ old('description') }}</textarea>
            </div>

            {{-- ASSESSMENT TYPE --}}
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Assessment Type</label>
                <select name="type" required
                        class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 
                               focus:border-blue-500 transition-all shadow-sm hover:shadow-md outline-none">
                    <option value="">Select type</option>
                    <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                    <option value="exam" {{ old('type') == 'exam' ? 'selected' : '' }}>Exam</option>
                    <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                    <option value="lab_exercise" {{ old('type') == 'lab_exercise' ? 'selected' : '' }}>Lab Exercise</option>
                </select>
            </div>

            {{-- DURATION (for quiz/exam) --}}
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Duration (in minutes)</label>
                <input type="number" name="duration" value="{{ old('duration') }}" min="1"
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 
                              focus:border-blue-500 transition-all shadow-sm hover:shadow-md outline-none"
                       placeholder="Leave blank for assignments">
                <p class="text-gray-500 text-sm mt-1">Only applicable for quizzes and exams</p>
            </div>

            {{-- ATTEMPTS ALLOWED --}}
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Attempts Allowed</label>
                <input type="number" name="attempts_allowed" value="{{ old('attempts_allowed', 1) }}" min="1" required
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 
                              focus:border-blue-500 transition-all shadow-sm hover:shadow-md outline-none">
            </div>

            {{-- SHOW SCORES --}}
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="show_scores" value="1" 
                           {{ old('show_scores') ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-3 text-gray-800 font-semibold">Show scores to students</span>
                </label>
            </div>

            {{-- START DATE --}}
            <div class="mb-6">
                <label class="block text-gray-800 font-semibold mb-2">Start Date & Time</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 
                              focus:border-blue-500 transition-all shadow-sm hover:shadow-md outline-none">
            </div>

            {{-- DUE DATE --}}
            <div class="mb-8">
                <label class="block text-gray-800 font-semibold mb-2">Due Date & Time</label>
                <input type="datetime-local" name="due_date" value="{{ old('due_date') }}"
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 
                              focus:border-blue-500 transition-all shadow-sm hover:shadow-md outline-none">
            </div>

            {{-- BUTTONS --}}
            <div class="flex items-center space-x-5">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-xl 
                               shadow-md hover:shadow-lg transition-all">
                    Create & Add Questions
                </button>

                <button type="reset"
                        class="bg-red-600 text-white font-semibold px-8 py-3 rounded-xl shadow-md 
                               hover:bg-red-700 hover:shadow-lg transition-all">
                    Reset
                </button>

                <a href="{{ route('module4.instructorAssessments.index', $course->id) }}"
                   class="text-gray-600 hover:text-gray-900 font-medium ml-4">
                    Back
                </a>
            </div>

        </form>
    </div>

</div>

@endsection