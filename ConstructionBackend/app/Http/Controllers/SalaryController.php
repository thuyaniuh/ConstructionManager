<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Entry;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryController extends Controller
{
    // Tính lương theo tháng và lưu lại lịch sử
    public function calculateSalary(Request $request, $user_id)
    {
        $month = $request->input('month') ?? Carbon::now()->month;
        $year = $request->input('year') ?? Carbon::now()->year;
        $rate = $request->input('rate', 100); // Mức lương theo giờ, mặc định là 100

        // Tính tổng số giờ làm việc trong tháng
        $totalHours = Entry::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('hour');

        // Tính tổng lương
        $totalSalary = $totalHours * $rate;

        // Tìm xem đã có bản ghi lương cho tháng này chưa
        $salary = Salary::where('user_id', $user_id)
            ->whereMonth('salary_month', $month)
            ->whereYear('salary_month', $year)
            ->first();

        if ($salary) {
            // Cập nhật thông tin nếu đã có bản ghi lương cho tháng này
            $remainingSalary = $totalSalary - ($salary->advance ?? 0); // Trừ số tiền đã ứng lương nếu có
            $salary->update([
                'time_entry' => $totalHours,
                'total' => $totalSalary,
                'remaining' => $remainingSalary,
                'salary_month' => Carbon::create($year, $month, 1),
            ]);

            return response()->json([
                'total_hours' => $totalHours,
                'total_salary' => $totalSalary,
                'advance' => $salary->advance,
                'remaining_salary' => $remainingSalary,
                'status' => $salary->status,
                'advance_date' => $salary->advance_date,
                'salary_month' => $salary->salary_month->format('Y-m'),
            ], 200);
        } else {
            // Tạo bản ghi mới nếu chưa có
            $salary = Salary::create([
                'user_id' => $user_id,
                'time_entry' => $totalHours,
                'rate' => $rate,
                'total' => $totalSalary,
                'status' => 'unpaid', // Mặc định là chưa trả lương
                'salary_month' => Carbon::create($year, $month, 1),
            ]);

            return response()->json([
                'total_hours' => $totalHours,
                'total_salary' => $totalSalary,
                'status' => 'unpaid',
                'salary_month' => Carbon::create($year, $month, 1)->format('Y-m'),
            ], 201);
        }
    }

    // Ứng lương
    public function advanceSalary(Request $request, $user_id)
    {
        $month = $request->input('month') ?? Carbon::now()->month;
        $year = $request->input('year') ?? Carbon::now()->year;
        $advanceAmount = $request->input('advance'); // Số tiền ứng lương

        // Tìm bản ghi lương của tháng hiện tại
        $salary = Salary::where('user_id', $user_id)
            ->whereMonth('salary_month', $month)
            ->whereYear('salary_month', $year)
            ->first();

        if (!$salary) {
            return response()->json(['message' => 'Lương chưa được tính toán cho tháng này'], 404);
        }

        // Cập nhật thông tin ứng lương
        $remainingSalary = $salary->total - $advanceAmount;

        $salary->update([
            'advance' => $advanceAmount,
            'advance_date' => Carbon::now(),
            'remaining' => $remainingSalary,
            'status' => 'advance', // Đổi trạng thái thành đã ứng lương
        ]);

        return response()->json([
            'message' => 'Đã ứng lương thành công',
            'advance' => $advanceAmount,
            'remaining_salary' => $remainingSalary,
            'status' => 'advance',
        ], 200);
    }

    // Đánh dấu lương là đã trả
    public function markAsPaid($user_id)
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $salary = Salary::where('user_id', $user_id)
            ->whereMonth('salary_month', $month)
            ->whereYear('salary_month', $year)
            ->first();

        if (!$salary) {
            return response()->json(['message' => 'Lương chưa được tính toán cho tháng này'], 404);
        }

        // Cập nhật trạng thái lương thành "paid"
        $salary->update([
            'status' => 'paid',
        ]);

        return response()->json(['message' => 'Lương đã được trả'], 200);
    }

    // Xem chi tiết lương
    public function showSalary($user_id, $month, $year)
    {
        $salary = Salary::where('user_id', $user_id)
            ->whereMonth('salary_month', $month)
            ->whereYear('salary_month', $year)
            ->first();

        if (!$salary) {
            return response()->json(['message' => 'Không tìm thấy bản ghi lương cho tháng này'], 404);
        }

        return response()->json([
            'total_hours' => $salary->time_entry,
            'total_salary' => $salary->total,
            'advance' => $salary->advance,
            'remaining_salary' => $salary->remaining,
            'status' => $salary->status,
            'advance_date' => $salary->advance_date,
            'salary_month' => $salary->salary_month->format('Y-m'),
        ], 200);
    }
}
