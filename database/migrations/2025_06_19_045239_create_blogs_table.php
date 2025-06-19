<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();  // Auto-increment ID
            $table->string('title');  // ব্লগের টাইটেল
            $table->text('content');  // ব্লগের কনটেন্ট
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // অথরের ইউজার ID
            $table->timestamps();  // created_at এবং updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
