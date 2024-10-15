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
        Schema::table('salaries', function (Blueprint $table) {
            // Thêm các cột mới nếu chưa tồn tại
            if (!Schema::hasColumn('salaries', 'advance')) {
                $table->double('advance')->nullable(); // Số tiền ứng trước
            }
            if (!Schema::hasColumn('salaries', 'advance_date')) {
                $table->date('advance_date')->nullable(); // Ngày ứng lương
            }
            if (!Schema::hasColumn('salaries', 'status')) {
                $table->enum('status', ['paid', 'unpaid', 'advance'])->default('unpaid'); // Trạng thái lương
            }
            if (!Schema::hasColumn('salaries', 'remaining')) {
                $table->double('remaining')->nullable(); // Số tiền còn lại sau khi ứng lương
            }
            if (!Schema::hasColumn('salaries', 'salary_month')) {
                $table->date('salary_month'); // Tháng lương được trả
            }
        });
    }

    public function down()
    {
        Schema::table('salaries', function (Blueprint $table) {
            // Xóa các cột mới đã thêm
            $table->dropColumn('advance');
            $table->dropColumn('advance_date');
            $table->dropColumn('status');
            $table->dropColumn('remaining');
            $table->dropColumn('salary_month');
        });
    }
};
