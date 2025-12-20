<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manage_courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->integer('duration'); // jam
            $table->unsignedBigInteger('teacher_id')->nullable(); // nanti connect ke users table
            $table->timestamps();
            $table->string('image_url')->nullable();
            $table->tinyInteger('difficulty')->default(1); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manage_courses');
    }
};
