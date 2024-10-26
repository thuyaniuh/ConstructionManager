<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Carbon\Carbon;
use Exception;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $projects = Project::latest()->get();
        return response()->json($projects, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $validated = $request->validate([
                'budget' => 'required',
                'name' => 'required',
                'type' => 'required|in:"Xây dựng","Thiết kế"',
                'description' => 'required|string|max:500',
                'start_day' => 'required|date',
                'end_day' => 'required|date',
                'status' => 'required|in:active,completed',
            ]);

            if(Carbon::parse($request->start_day)->greaterThanOrEqualTo(Carbon::parse($request->end_day))) {
                return response()->json("start date không được nhỏ hơn end date", 500);
            }

            $project = Project::create($validated);

            return response()->json($project, 201);
        } catch(Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
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
}
