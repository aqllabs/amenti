<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Step 1: Add course_id column without non-null constraint
        Schema::table('user_lesson_progress', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable();
        });

        // Step 2: Populate course_id based on existing relationships
        DB::statement('
            UPDATE user_lesson_progress
            SET course_id = (
                SELECT course_id
                FROM lessons
                WHERE lessons.id = user_lesson_progress.lesson_id
            )
        ');

        // Step 3: Add non-null constraint and foreign key
        Schema::table('user_lesson_progress', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable(false)->change();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('user_lesson_progress', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
    }
};
