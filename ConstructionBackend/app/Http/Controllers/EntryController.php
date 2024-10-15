<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use Illuminate\Support\Carbon;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $entries = Entry::all();
        return response()->json($entries, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time', // Đảm bảo end_time sau start_time
            'note' => 'required|string|max:255',
        ]);

        // Tính toán số giờ làm việc
        $start_time = Carbon::parse($validated['start_time']);
        $end_time = Carbon::parse($validated['end_time']);
        $hours = $end_time->diffInHours($start_time); // Tính số giờ làm

        // Tạo bản ghi mới trong bảng Entry
        $entry = Entry::create([
            'project_id' => $validated['project_id'],
            'user_id' => $validated['user_id'],
            'start_time' => $start_time,
            'end_time' => $end_time,
            'hour' => $hours, // Lưu số giờ vào cột hour
            'note' => $validated['note'],
        ]);

        return response()->json($entry, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entry = Entry::findOrFail($id);

        return response()->json($entry, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $entry = Entry::findOrFail($id);

        // Xác thực dữ liệu đầu vào
        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:projects,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'note' => 'sometimes|required|string|max:255',
        ]);

        // Nếu start_time hoặc end_time được cập nhật, tính toán lại số giờ làm việc
        if (isset($validated['start_time']) && isset($validated['end_time'])) {
            $start_time = Carbon::parse($validated['start_time']);
            $end_time = Carbon::parse($validated['end_time']);
            $hours = $end_time->diffInHours($start_time); // Tính số giờ làm
            $validated['hour'] = $hours; // Lưu số giờ vào cột hour
        }

        // Cập nhật bản ghi
        $entry->update($validated);

        return response()->json($entry, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $entry = Entry::findOrFail($id);
        $entry->delete();

        return response()->json(null, 204);
    }
}
