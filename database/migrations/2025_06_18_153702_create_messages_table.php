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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');  // সেন্ডার ইউজার
            $table->foreignId('receiver_id')->constrained('users');  // রিসিভার ইউজার
            $table->string('type');  // টাইপ: 'text', 'video', 'audio'
            $table->text('content')->nullable();  // টেক্সটের জন্য
            $table->string('file_path')->nullable();  // ভিডিও/অডিও ফাইলের পাথ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
