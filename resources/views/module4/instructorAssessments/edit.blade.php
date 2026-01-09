@extends('Module1.teacher.layout')

@section('content')

<div class="max-w-6xl mx-auto py-10">

    {{-- PAGE TITLE --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Assessment</h1>
        <span class="px-4 py-2 rounded-lg font-semibold
            @if($assessment->status == 'published') bg-green-100 text-green-800
            @elseif($assessment->status == 'draft') bg-gray-100 text-gray-800
            @else bg-red-100 text-red-800
            @endif">
            {{ ucfirst($assessment->status) }}
        </span>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- ASSESSMENT DETAILS CARD --}}
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100 mb-6">
        
        <div class="mb-6 pb-3 border-b border-gray-200">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">
                Assessment Settings
            </h2>
        </div>

        <form action="{{ route('module4.instructorAssessments.update', [$course->id, $assessment->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-6 mb-6">
                {{-- TITLE --}}
                <div class="col-span-2">
                    <label class="block text-gray-800 font-semibold mb-2">Title</label>
                    <input type="text" name="title" value="{{ $assessment->title }}" required
                           class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- DESCRIPTION --}}
                <div class="col-span-2">
                    <label class="block text-gray-800 font-semibold mb-2">Description</label>
                    <textarea name="description" rows="2"
                              class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">{{ $assessment->description }}</textarea>
                </div>

                {{-- TYPE --}}
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Type</label>
                    <select name="type" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="quiz" {{ $assessment->type == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="exam" {{ $assessment->type == 'exam' ? 'selected' : '' }}>Exam</option>
                        <option value="assignment" {{ $assessment->type == 'assignment' ? 'selected' : '' }}>Assignment</option>
                        <option value="lab_exercise" {{ $assessment->type == 'lab_exercise' ? 'selected' : '' }}>Lab Exercise</option>
                    </select>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Status</label>
                    <select name="status" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="draft" {{ $assessment->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ $assessment->status == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="closed" {{ $assessment->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                {{-- DURATION --}}
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Duration (mins)</label>
                    <input type="number" name="duration" value="{{ $assessment->duration }}" min="1"
                           class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- ATTEMPTS --}}
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Attempts Allowed</label>
                    <input type="number" name="attempts_allowed" value="{{ $assessment->attempts_allowed }}" min="1" required
                           class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- START DATE --}}
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Start Date</label>
                    <input type="datetime-local" name="start_date" 
                           value="{{ $assessment->start_date ? $assessment->start_date->format('Y-m-d\TH:i') : '' }}"
                           class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- DUE DATE --}}
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Due Date</label>
                    <input type="datetime-local" name="due_date" 
                           value="{{ $assessment->due_date ? $assessment->due_date->format('Y-m-d\TH:i') : '' }}"
                           class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- SHOW SCORES --}}
                <div class="col-span-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="show_scores" value="1" {{ $assessment->show_scores ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-3 text-gray-800 font-semibold">Show scores to students</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl shadow-md transition-all">
                Update Assessment
            </button>
        </form>
    </div>

    {{-- QUESTIONS SECTION (for Quiz/Exam only) --}}
    @if($assessment->isQuizType())
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
        
        <div class="mb-6 pb-3 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-purple-400 bg-clip-text text-transparent">
                    Questions
                </h2>
                <p class="text-gray-600 mt-1">Total Marks: {{ $assessment->total_marks }}</p>
            </div>
            <button onclick="document.getElementById('addQuestionModal').classList.remove('hidden')"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-6 py-3 rounded-xl shadow-md transition-all">
                + Add Question
            </button>
        </div>

        {{-- QUESTIONS LIST --}}
        @if($assessment->questions->isEmpty())
            <p class="text-gray-500 text-center py-8">No questions added yet. Click "Add Question" to start.</p>
        @else
            <div class="space-y-4">
                @foreach($assessment->questions as $index => $question)
                    <div class="border border-gray-200 rounded-xl p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        Q{{ $index + 1 }}
                                    </span>
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $question->marks }} marks
                                    </span>
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                                        {{ str_replace('_', ' ', ucfirst($question->question_type)) }}
                                    </span>
                                </div>
                                <p class="text-gray-800 font-medium mb-3">{{ $question->question_text }}</p>
                                
                                @if($question->options->isNotEmpty())
                                    <div class="pl-4 space-y-2">
                                        @foreach($question->options as $option)
                                            <div class="flex items-center">
                                                <span class="mr-2">{{ $option->is_correct ? '✓' : '○' }}</span>
                                                <span class="{{ $option->is_correct ? 'text-green-700 font-semibold' : 'text-gray-600' }}">
                                                    {{ $option->option_text }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2">
    <form
        action="{{ route('module4.instructorAssessments.questions.destroy', [$course->id, $assessment->id, $question->id]) }}"
        method="POST"
        onsubmit="return confirm('Delete this question?')"
    >
        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="text-red-600 hover:text-red-800 font-semibold text-sm"
        >
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
    @endif

    {{-- BACK BUTTON --}}
    <div class="mt-6">
        <a href="{{ route('module4.instructorAssessments.index', $course->id) }}">

            ← Back to Assessments
        </a>
    </div>

</div>

{{-- ADD QUESTION MODAL --}}
<div id="addQuestionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Add New Question</h3>
        
        <form action="{{ route('module4.instructorAssessments.questions.store', [$course->id, $assessment->id]) }}" method="POST">
            @csrf
            
            {{-- QUESTION TEXT --}}
            <div class="mb-4">
                <label class="block text-gray-800 font-semibold mb-2">Question</label>
                <textarea name="question_text" rows="3" required
                          class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none"></textarea>
            </div>

            {{-- QUESTION TYPE --}}
            <div class="mb-4">
                <label class="block text-gray-800 font-semibold mb-2">Question Type</label>
                <select name="question_type" id="questionType" required
                        class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none"
                        onchange="toggleOptions()">
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="true_false">True/False</option>
                    <option value="short_answer">Short Answer</option>
                    <option value="long_answer">Long Answer</option>
                </select>
            </div>

            {{-- MARKS --}}
            <div class="mb-4">
                <label class="block text-gray-800 font-semibold mb-2">Marks</label>
                <input type="number" name="marks" value="1" min="1" required
                       class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
            </div>

            {{-- OPTIONS (for multiple choice) --}}
            <div id="optionsSection" class="mb-4">
                <label class="block text-gray-800 font-semibold mb-2">Answer Options</label>
                <div id="optionsList" class="space-y-2 mb-3">
                    <div class="flex gap-2">
                        <input type="radio" name="correct_answer" value="0" required>
                        <input type="text" name="options[0][text]" placeholder="Option 1" required
                               class="flex-1 p-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="flex gap-2">
                        <input type="radio" name="correct_answer" value="1" required>
                        <input type="text" name="options[1][text]" placeholder="Option 2" required
                               class="flex-1 p-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <button type="button" onclick="addOption()" 
                        class="text-purple-600 hover:text-purple-800 font-semibold text-sm">
                    + Add Option
                </button>
            </div>

            {{-- BUTTONS --}}
            <div class="flex gap-3">
                <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-6 py-3 rounded-xl transition-all">
                    Add Question
                </button>
                <button type="button" onclick="document.getElementById('addQuestionModal').classList.add('hidden')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-3 rounded-xl transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let optionCount = 2;

function setOptionsRequired(isRequired) {
    document.querySelectorAll('input[name="correct_answer"]').forEach(el => {
        if (isRequired) {
            el.setAttribute('required', 'required');
        } else {
            el.removeAttribute('required');
            el.checked = false;
        }
    });

    document.querySelectorAll('#optionsList input[type="text"]').forEach(el => {
        if (isRequired) {
            el.setAttribute('required', 'required');
        } else {
            el.removeAttribute('required');
            el.value = '';
        }
    });
}

function addOption() {
    const optionsList = document.getElementById('optionsList');
    const newOption = document.createElement('div');
    newOption.className = 'flex gap-2';
    newOption.innerHTML = `
        <input type="radio" name="correct_answer" value="${optionCount}">
        <input type="text" name="options[${optionCount}][text]" placeholder="Option ${optionCount + 1}"
               class="flex-1 p-2 border border-gray-300 rounded-lg">
    `;
    optionsList.appendChild(newOption);
    optionCount++;
}

function toggleOptions() {
    const questionType = document.getElementById('questionType').value;
    const optionsSection = document.getElementById('optionsSection');

    if (questionType === 'multiple_choice') {
        optionsSection.classList.remove('hidden');
        setOptionsRequired(true);
    }
    else if (questionType === 'true_false') {
        optionsSection.classList.remove('hidden');
        setOptionsRequired(true);
    }
    else {
        // 🔥 INI FIX PALING PENTING
        optionsSection.classList.add('hidden');
        setOptionsRequired(false);
    }
}
</script>


@endsection