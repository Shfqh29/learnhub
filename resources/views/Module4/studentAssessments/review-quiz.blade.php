@extends('layouts.learnhub')

@section('content')

<div class="max-w-5xl mx-auto py-10">

    {{-- PAGE TITLE --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Review: {{ $assessment->title }}</h1>
        <p class="text-gray-600 mt-1">Attempt #{{ $attempt->attempt_number }}</p>
    </div>

    {{-- SCORE SUMMARY --}}
    @if($assessment->show_scores)
    <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100 mb-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Score</h2>
            <div class="inline-block bg-blue-50 border-2 border-blue-500 rounded-xl p-6">
                <p class="text-5xl font-bold text-blue-600">
                    {{ $attempt->marks_obtained }}/{{ $assessment->total_marks }}
                </p>
                <p class="text-xl text-gray-600 mt-2">
                    {{ round(($attempt->marks_obtained / $assessment->total_marks) * 100, 2) }}%
                </p>
            </div>
            <p class="text-gray-600 mt-4">Completed on {{ $attempt->completed_at->format('M d, Y H:i') }}</p>
        </div>
    </div>
    @endif

    {{-- QUESTIONS REVIEW --}}
    <div class="space-y-6">
        @foreach($assessment->questions as $index => $question)
            @php
                $answer = $attempt->answers->where('question_id', $question->id)->first();
            @endphp

            <div class="bg-white shadow-lg rounded-2xl p-8 border-l-4
                @if($answer && $answer->is_correct) border-green-500
                @elseif($answer && !$answer->is_correct && in_array($question->question_type, ['multiple_choice', 'true_false'])) border-red-500
                @else border-gray-300
                @endif">
                
                {{-- QUESTION HEADER --}}
                <div class="flex items-start gap-4 mb-4">
                    <span class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold text-lg">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1">
                        <p class="text-lg text-gray-800 font-medium">{{ $question->question_text }}</p>
                        <div class="flex gap-4 mt-2 text-sm">
                            <span class="text-gray-500">({{ $question->marks }} mark{{ $question->marks > 1 ? 's' : '' }})</span>
                            @if($answer && in_array($question->question_type, ['multiple_choice', 'true_false']))
                                <span class="font-semibold
                                    @if($answer->is_correct) text-green-600
                                    @else text-red-600
                                    @endif">
                                    {{ $answer->is_correct ? '✓ Correct' : '✗ Incorrect' }}
                                    ({{ $answer->marks_obtained }}/{{ $question->marks }})
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ANSWER REVIEW --}}
                <div class="ml-16">
                    @if($question->question_type == 'multiple_choice' || $question->question_type == 'true_false')
                        <div class="space-y-2">
                            @foreach($question->options as $option)
                                <div class="p-3 rounded-lg border-2
                                    @if($answer && $answer->question_option_id == $option->id && $option->is_correct)
                                        bg-green-50 border-green-500
                                    @elseif($answer && $answer->question_option_id == $option->id && !$option->is_correct)
                                        bg-red-50 border-red-500
                                    @elseif($option->is_correct)
                                        bg-green-50 border-green-300
                                    @else
                                        border-gray-200
                                    @endif">
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            @if($answer && $answer->question_option_id == $option->id)
                                                <span class="mr-3">👉</span>
                                            @endif
                                            <span class="text-gray-800">{{ $option->option_text }}</span>
                                        </div>
                                        
                                        @if($option->is_correct)
                                            <span class="text-green-600 font-semibold text-sm">✓ Correct Answer</span>
                                        @elseif($answer && $answer->question_option_id == $option->id)
                                            <span class="text-red-600 font-semibold text-sm">Your Answer</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @elseif($question->question_type == 'short_answer' || $question->question_type == 'long_answer')
                        <div class="bg-gray-50 border border-gray-300 rounded-lg p-4">
                            <p class="text-gray-800 font-semibold mb-2">Your Answer:</p>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $answer ? $answer->answer_text : 'No answer provided' }}</p>
                            
                            @if($answer && $answer->marks_obtained > 0)
                                <div class="mt-3 pt-3 border-t border-gray-300">
                                    <p class="text-green-600 font-semibold">
                                        Score: {{ $answer->marks_obtained }}/{{ $question->marks }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        @endforeach
    </div>

    {{-- BACK BUTTON --}}
    <div class="mt-8">
        <a href="{{ route('module4.studentAssessments.show', [$assessment->course_id, $assessment->id]) }}" 
           class="text-gray-600 hover:text-gray-900 font-medium">
            ← Back to Assessment
        </a>
    </div>

</div>

@endsection