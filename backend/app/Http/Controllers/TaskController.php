<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index(IndexTaskRequest $request)
    {
        $filters = $request->validated();
        $page = $filters['page'] ?? 1;
        $tasks = $this->taskService->getTasks(filters: $filters, page: $page);
        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = $this->taskService->getTaskById($id);
        return response()->json($task);
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        if($validated) {
            $this->taskService->createTask($validated);
        }
        return response()->json(['message' => 'Task created successfully'], 201);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $validated = $request->validated();
        if($validated) {
            $this->taskService->update($id, $validated);
        }
        return response()->json(['message' => 'Task updated successfully']);
    }

    public function updateStatus($id, $status)
    {
        $this->taskService->update($id, ['status' => $status]);
        return response()->json(['message' => 'Task status updated successfully']);
    }

    public function destroy($id)
    {
        $deleted = $this->taskService->delete($id);
        if ($deleted) {
            return response()->json(['message' => 'Task deleted successfully']);
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }
}
