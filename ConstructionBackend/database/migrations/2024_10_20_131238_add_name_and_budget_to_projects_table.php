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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('name', 255)->after('project_id'); // Thêm cột tên dự án
            $table->decimal('budget', 15, 2)->after('end_day'); // Thêm cột ngân sách
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('budget');
        });
    }
};
