<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskPriorityRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index(IndexTaskRequest $request)
    {
        $filters = $request->validated();
        $perPage = $filters['per_page'] ?? 20;
        $tasks = $this->taskService->getTasks(filters: $filters, perPage: $perPage);
        return response()->json($tasks);
    }

    public function show($id)
    {
        $perPage = request()->get('per_page', 10);
        $task = $this->taskService->getTaskById($id, $perPage);
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

    public function updateStatus(UpdateTaskStatusRequest $request, $id)
    {
        $validated = $request->validated();
        $status = $validated['status'];
        $this->taskService->update($id, ['status' => $status]);
        return response()->json(['message' => 'Task status updated successfully']);
    }

    public function updatePriority(UpdateTaskPriorityRequest $request, $id)
    {
        $validated = $request->validated();
        $priority = $validated['priority'];
        $this->taskService->update($id, ['priority' => $priority]);
        return response()->json(['message' => 'Task priority updated successfully']);
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
