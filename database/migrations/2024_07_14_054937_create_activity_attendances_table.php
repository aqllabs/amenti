<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->enum('status', ['ATTENDED', 'ACCEPTED', 'NOSHOW', 'REJECTED'])->nullable();
            $table->unsignedBigInteger('activity_id');
            $table->integer('rating')->nullable();
            $table->text('feedback')->nullable();
            $table->text('admin_feedback')->nullable();


            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['activity_id', 'user_id'], 'unique_activity_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_attendances');
    }
};
