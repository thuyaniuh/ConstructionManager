<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use App\Models\ProjectAssignment;

class EntryController extends Controller
{
    // Lấy danh sách chấm công theo dự án
    public function index($project_id)
    {
        $entries = Entry::where('project_id', $project_id)
            ->with('user') // Lấy thông tin người dùng
            ->get();

        return response()->json($entries, 200);
    }

    // Tạo chấm công cho người dùng trong dự án
    public function createEntries(Request $request, $project_id)
    {
        $validated = $request->validate([
            'users' => 'required|array', // Mảng chứa thông tin người dùng và note
            'users.*.user_id' => 'required|exists:users,id',
            'users.*.note' => 'required|in:Có mặt,Trễ,Vắng',
        ]);

        foreach ($validated['users'] as $user) {
            // Kiểm tra xem người dùng có được phân công vào dự án không
            $assignment = ProjectAssignment::where('project_id', $project_id)
                ->where('user_id', $user['user_id'])
                ->first();

            if ($assignment) {
                Entry::create([
                    'project_id' => $project_id,
                    'user_id' => $user['user_id'],
                    'start_time' => now(),
                    'note' => $user['note'],
                ]);
            }
        }

        return response()->json(['message' => 'Entries created successfully'], 201);
    }

    // Check-out người dùng
    public function checkout($id)
    {
        $entry = Entry::findOrFail($id);
        $entry->end_time = now();
        $entry->save();

        return response()->json($entry, 200);
    }
}
