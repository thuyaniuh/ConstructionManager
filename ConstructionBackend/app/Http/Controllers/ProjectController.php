<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $projects = Project::all();
        return response()->json($projects, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:construction,design',
            'start_day' => 'required|date',
            'end_day' => 'required|date|after_or_equal:start_day',  // Ràng buộc ngày kết thúc sau hoặc bằng ngày bắt đầu
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:in-progress,completed,on-hold',
            'description' => 'nullable|string',
        ]);

        // Xử lý logic tạo dự án
        Project::create($request->all());

        return response()->json(['message' => 'Dự án đã được tạo thành công'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $project = Project::findOrFail($id);
        return response()->json($project, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'type' => 'sometimes|required|in:"Xây dựng","Thiết kế"',
            'description' => 'sometimes|required|string|max:500',
            'start_day' => 'sometimes|required|date',
            'end_day' => 'sometimes|required|date',
            'status' => 'required|in:active,completed',
        ]);

        $project->update($validated);

        return response()->json($project, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(null, 204);
    }

    public function countProject()
    {
        $count = Project::count();
        return response()->json(['count' => $count], 200);
    }
}
