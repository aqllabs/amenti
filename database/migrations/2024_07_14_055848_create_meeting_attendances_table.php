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
        Schema::create('meeting_attendances', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->enum('status', ['NOSHOW', 'ATTENDED', 'ACCEPTED', 'REJECTED', 'INVITED'])->default('INVITED');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->uuid('meeting_id')->nullable();
            $table->integer('rating')->nullable();
            $table->text('feedback')->nullable();
            $table->text('admin_feedback')->nullable();

            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_attendances');
    }
};
