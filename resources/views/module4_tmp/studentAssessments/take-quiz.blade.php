@extends('Module1.student.layout')

@section('content')

<div class="max-w-5xl mx-auto py-10">

    {{-- ================= TIMER BAR ================= --}}
    @if($assessment->duration)
    <div class="bg-white shadow-lg rounded-xl p-4 mb-6 sticky top-4 z-10 border-2 border-blue-500">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">{{ $assessment->title }}</h3>
            <div class="flex items-center gap-4">
                <span class="text-gray-600">Time Remaining:</span>
                <span id="timer" class="text-2xl font-bold text-red-600">--:--</span>
            </div>
        </div>
        <div class="mt-2 bg-gray-200 rounded-full h-2">
            <div id="progress" class="bg-blue-600 h-2 rounded-full transition-all" style="width:100%"></div>
        </div>
    </div>
    @endif

    {{-- ================= QUIZ FORM ================= --}}
    <form id="quizForm"
          action="{{ route('module4.studentAssessments.submit', [$assessment->course_id, $assessment->id, $attempt->id]) }}"
          method="POST">
        @csrf

        {{-- QUESTIONS --}}
        <div class="space-y-6">
            @foreach($assessment->questions as $index => $question)
                <div class="bg-white shadow-lg rounded-2xl p-8 border border-gray-100">

                    {{-- QUESTION HEADER --}}
                    <div class="flex items-start gap-4 mb-4">
                        <span class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold text-lg">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <p class="text-lg text-gray-800 font-medium">
                                {{ $question->question_text }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                ({{ $question->marks }} mark{{ $question->marks > 1 ? 's' : '' }})
                            </p>
                        </div>
                    </div>

                    {{-- ANSWERS --}}
                    <div class="ml-16">
                        @if(in_array($question->question_type, ['multiple_choice','true_false']))
                            <div class="space-y-3">
                                @foreach($question->options as $option)
                                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition-all">
                                        <input type="radio"
                                               name="answers[{{ $question->id }}][option_id]"
                                               value="{{ $option->id }}"
                                               required
                                               class="w-5 h-5 text-blue-600">
                                        <span class="ml-3 text-gray-800">
                                            {{ $option->option_text }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                        @elseif($question->question_type == 'short_answer')
                            <input type="text" 
       name="answers[{{ $question->id }}][text]"
       autocomplete="off"
       class="w-full p-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
       placeholder="Type your answer here"
       required>

                        @elseif($question->question_type == 'long_answer')
                            <textarea name="answers[{{ $question->id }}][text]" 
          rows="6"
          autocomplete="off"
          class="w-full p-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
          placeholder="Type your detailed answer here"
          required></textarea>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

        {{-- SUBMIT --}}
        <div class="mt-8 bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex justify-between items-center">
                <p class="text-gray-600">
                    Make sure you have answered all questions before submitting.
                </p>

                <button type="submit"
                        onclick="isSubmitting = confirm('Are you sure you want to submit? You cannot change your answers after submission.')"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-xl">
                    Submit Assessment
                </button>
            </div>
        </div>

    </form>

</div>

{{-- ================= TIMER SCRIPT ================= --}}
@if($assessment->duration)
<script>
    const durationSeconds = {{ $assessment->duration * 60 }};

    @if($assessment->due_date)
        const dueTime = new Date("{{ $assessment->due_date->format('Y-m-d H:i:s') }}").getTime();
        const nowTime = new Date().getTime();
        let timeInSeconds = Math.floor((dueTime - nowTime) / 1000);
        timeInSeconds = Math.min(timeInSeconds, durationSeconds);
    @else
        let timeInSeconds = durationSeconds;
    @endif

    const totalTime = timeInSeconds;
    const timerDisplay = document.getElementById('timer');
    const progressBar = document.getElementById('progress');
    const form = document.getElementById('quizForm');

    let isSubmitting = false;

    if (timeInSeconds <= 0) {
        alert('This exam has already ended.');
        window.location.href = "{{ route('module4.studentAssessments.show', [$assessment->course_id, $assessment->id]) }}";
    }

    function updateTimer() {
        const minutes = Math.floor(timeInSeconds / 60);
        const seconds = timeInSeconds % 60;

        timerDisplay.textContent =
            `${minutes}:${seconds.toString().padStart(2, '0')}`;

        progressBar.style.width =
            ((timeInSeconds / totalTime) * 100) + '%';

        if (timeInSeconds <= 60) {
            timerDisplay.classList.add('animate-pulse');
        }

        if (timeInSeconds <= 0) {
            alert('Time is up! Your assessment will be submitted automatically.');
            isSubmitting = true;
            form.submit();
        }

        timeInSeconds--;
    }

    setInterval(updateTimer, 1000);
    updateTimer();

    window.addEventListener('beforeunload', function (e) {
        if (!isSubmitting && timeInSeconds > 0) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // ================= AUTOSAVE (LOCAL) =================
    const autosaveKey = "autosave_attempt_{{ $attempt->id }}";

    // Save answers every 5 seconds
    setInterval(() => {
        let data = {};

        document.querySelectorAll("input, textarea").forEach(el => {
            if (el.type === "radio") {
                if (el.checked) data[el.name] = el.value;
            } else {
                data[el.name] = el.value;
            }
        });

        localStorage.setItem(autosaveKey, JSON.stringify(data));
        console.log("Autosaved...");
    }, 5000);

    // Restore answers on reload
    window.addEventListener("DOMContentLoaded", () => {
        const saved = localStorage.getItem(autosaveKey);
        if (!saved) return;

        const data = JSON.parse(saved);
        Object.keys(data).forEach(name => {
            const el = document.querySelector(`[name="${name}"]`);
            if (!el) return;

            if (el.type === "radio") {
                const radio = document.querySelector(`[name="${name}"][value="${data[name]}"]`);
                if (radio) radio.checked = true;
            } else {
                el.value = data[name];
            }
        });
    });

    // Clear autosave after submit
    document.getElementById("quizForm").addEventListener("submit", () => {
        localStorage.removeItem(autosaveKey);
    });
    
</script>
@endif

@endsection
