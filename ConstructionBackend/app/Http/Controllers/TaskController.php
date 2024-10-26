<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        //
        $tasks = Task::where("project_id", $id)->with(['project', 'user'])->get();
        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // $validated = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'project_id' => 'required|exists:projects,id',
        //     'user_id' => 'required|exists:users,id',
        //     'start_day' => 'required|date',
        //     'end_day' => 'required|date',
        //     'status' => 'required|int',
        // ]);

        if(Carbon::parse($request->start_day)->greaterThanOrEqualTo(Carbon::parse($request->end_day))) {
            return response()->json("start date không được nhỏ hơn end date", 500);
        }
        // Thư viện carbon làm việc với ngày tháng nă, parse dùng để định dạng, hàm gte
        // $task = Task::create($validated);
        $task = Task::create($request->all());

        return response()->json($task, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $task = Task::findOrFail($id);
        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'project_id' => 'sometimes|required|exists:projects,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'start_day' => 'sometimes|required|date',
            'end_day' => 'sometimes|required|date',
            'status' => 'required|int',
        ]);

        $task->update($validated);

        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
}
