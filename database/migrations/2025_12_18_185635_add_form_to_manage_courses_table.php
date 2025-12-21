<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormToManageCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('manage_courses', function (Blueprint $table) {
            $table->unsignedTinyInteger('form')->nullable();
        });
    }

    public function down()
    {
        Schema::table('manage_courses', function (Blueprint $table) {
            $table->dropColumn('form');
        });
    }
}
