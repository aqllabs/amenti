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
        Schema::table('user_course', function (Blueprint $table) {
            $table->integer('progress')->default(0)->after('course_id');
        });

        // Update progress based on completed lessons
        DB::statement('
            UPDATE user_course uc
            SET progress = (
                SELECT COUNT(DISTINCT ulp.lesson_id)
                FROM user_lesson_progress ulp
                WHERE ulp.user_id = uc.user_id AND ulp.course_id = uc.course_id
            )
        ');
    }

    public function down()
    {
        Schema::table('user_course', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
