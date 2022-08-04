<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $tasks = Task::where('user_id', $user_id)->get();
        if (count($tasks) > 0) {
            return response()->json(['status' => 'success', 'data' => $tasks], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No tasks found'], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            "task_title" => "required",
            "task_description" => "required",
            "task_status" => "required"
        ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user_id = auth()->user()->id;
        $task = Task::create([
            "task_title" => $request->task_title,
            "task_description" => $request->task_description,
            "task_status" => $request->task_status,
            "user_id" => $user_id,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $task,
            'user_id' => $user_id]
            , 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = auth()->user()->id;
        $task = Task::where(
            ['id' => $id, 'user_id' => $user_id]
        )->get();

        if (count($task) == 1) {
            return response()->json(['status' => 'success', 'data' => $task], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Task not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            "task_title" => "required",
            "task_description" => "required",
        ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user_id = auth()->user()->id;
        $task = Task::where(
            ['id' => $id, 'user_id' => $user_id]
        )->update([
            "task_title" => $request->task_title,
            "task_description" => $request->task_description
        ]);

        $task_view = Task::where('id', $id)->get();

        if ($task) {
            return response()->json(['status' => 'success', 'data' => $task_view], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Task not found'], 404);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            "task_status" => "required",
        ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user_id = auth()->user()->id;
        $task = Task::where(
            ['id' => $id, 'user_id' => $user_id]
        )->update([
            "task_status" => $request->task_status,
        ]);

        $task_view = Task::where('id', $id)->get();

        if ($task) {
            return response()->json(['status' => 'success', 'data' => $task_view], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Task not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id = auth()->user()->id;
        $task = Task::where(
            ['id' => $id, 'user_id' => $user_id]
        )->delete();
        if ($task) {
            return response()->json(['status' => 'success', 'message' => 'Task deleted successfully'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Task not found'], 404);
        }
    }
}
