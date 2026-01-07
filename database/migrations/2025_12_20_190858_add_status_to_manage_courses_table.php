<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('manage_courses', function (Blueprint $table) {
        $table->string('status_course')->default('PENDING APPROVAL');
    });
}

public function down()
{
    Schema::table('manage_courses', function (Blueprint $table) {
        $table->dropColumn('status_course');
    });
}

};
