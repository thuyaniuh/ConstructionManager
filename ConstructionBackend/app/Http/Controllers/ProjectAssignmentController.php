<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectAssignment;

class ProjectAssignmentController extends Controller
{
    // Lấy danh sách người dùng phân công cho dự án
    public function index($project_id)
    {
        $assignments = ProjectAssignment::where('project_id', $project_id)
            ->with('user') // Lấy thông tin người dùng
            ->get();

        return response()->json($assignments, 200);
    }

    // Phân công người dùng vào dự án
    public function assignUsers(Request $request, $project_id)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|exists:users,id',
        ]);

        foreach ($validated['user_ids'] as $user_id) {
            ProjectAssignment::firstOrCreate([
                'project_id' => $project_id,
                'user_id' => $user_id,
            ]);
        }

        return response()->json(['message' => 'Users assigned successfully'], 201);
    }

    // Xóa phân công của người dùng trong dự án
    public function removeUserFromProject($assignment_id)
    {
        $assignment = ProjectAssignment::findOrFail($assignment_id);
        $assignment->delete();

        return response()->json(['message' => 'User removed from project'], 200);
    }
}
