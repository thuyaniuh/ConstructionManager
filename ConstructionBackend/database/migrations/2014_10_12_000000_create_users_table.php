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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('password');  // Độ dài mặc định là 255, bạn có thể giữ nguyên
            $table->string('name', 100);
            $table->date('birth');
            $table->string('phone', 10);
            $table->string('email', 50)->unique();  // Đặt unique trên email với độ dài phù hợp
            $table->enum('role', ['admin', 'worker', 'manager']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
