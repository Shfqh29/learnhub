<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                  ->constrained('manage_courses')
                  ->cascadeOnDelete();

            $table->foreignId('title_id')
                  ->constrained('titles')
                  ->cascadeOnDelete();

            $table->string('item_name');   // Content name
            $table->string('file_path');
            $table->string('file_type');   // pdf, image, video
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
