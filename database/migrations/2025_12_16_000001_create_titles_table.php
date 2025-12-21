<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('titles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                  ->constrained('manage_courses')
                  ->cascadeOnDelete();

            $table->string('title'); // Week 1, Week 2
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titles');
    }
};
