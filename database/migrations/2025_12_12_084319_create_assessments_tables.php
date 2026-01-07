<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Assessments table - stores quiz/exam/assignment details
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('type', ['quiz', 'exam', 'assignment', 'lab_exercise']);
            $table->integer('total_marks')->default(0);
            $table->integer('duration')->nullable()->comment('in minutes, for quiz/exam');
            $table->integer('attempts_allowed')->default(1);
            $table->tinyInteger('show_scores')->default(1)->comment('0=false, 1=true');
            $table->datetime('start_date')->nullable();
            $table->datetime('due_date')->nullable();
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('course_id')->references('id')->on('manage_courses')->onDelete('cascade');
        });

        // Questions table - for quiz/exam questions
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'short_answer', 'long_answer']);
            $table->integer('marks')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Foreign key
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
        });

        // Question options - for multiple choice and true/false
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->text('option_text');
            $table->tinyInteger('is_correct')->default(0)->comment('0=false, 1=true');
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Foreign key
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });

        // Student submissions - for assignments/lab exercises
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('user_id');
            $table->text('submission_text')->nullable();
            $table->string('file_path', 255)->nullable();
            $table->text('comments')->nullable();
            $table->integer('marks_obtained')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'submitted', 'graded'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Student attempts - for quiz/exam attempts
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('attempt_number')->default(1);
            $table->integer('marks_obtained')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['in_progress', 'completed', 'reviewed'])->default('in_progress');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Student answers - for quiz/exam answers
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('question_option_id')->nullable();
            $table->text('answer_text')->nullable();
            $table->integer('marks_obtained')->default(0);
            $table->tinyInteger('is_correct')->default(0)->comment('0=false, 1=true');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('attempt_id')->references('id')->on('attempts')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('question_option_id')->references('id')->on('question_options')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('attempts');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('assessments');
    }
};