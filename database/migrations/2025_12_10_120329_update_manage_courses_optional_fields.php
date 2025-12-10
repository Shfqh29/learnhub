<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('manage_courses', function (Blueprint $table) {
        $table->string('category')->nullable()->change();
        $table->integer('duration')->nullable()->change();
    });
}

public function down()
{
    Schema::table('manage_courses', function (Blueprint $table) {
        $table->string('category')->nullable(false)->change();
        $table->integer('duration')->nullable(false)->change();
    });
}
};
