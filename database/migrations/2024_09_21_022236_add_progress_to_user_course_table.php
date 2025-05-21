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
        Schema::table('user_course', function (Blueprint $table) {
            $table->integer('progress')->default(0)->after('course_id');
        });
    }

    public function down()
    {
        Schema::table('user_course', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
