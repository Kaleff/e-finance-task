<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexTaskRequest;
use App\Http\Requests\ShowRequest;
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

    public function show(ShowRequest $request)
    {
        $data = $request->validated();
        $id = $data['id'];
        $perPage = $data['per_page'] ?? 10;
        $task = $this->taskService->getTaskById($id, $perPage);
        if(!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
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
            $task = $this->taskService->update($id, $validated);

            if(!$task) {
                return response()->json(['message' => 'Task not found'], 404);
            }
        }
        return response()->json(['message' => 'Task updated successfully']);
    }

    public function updateStatus(UpdateTaskStatusRequest $request, $id)
    {
        $validated = $request->validated();
        $status = $validated['status'];
        $task = $this->taskService->update($id, ['status' => $status]);
        if(!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json(['message' => 'Task status updated successfully']);
    }

    public function updatePriority(UpdateTaskPriorityRequest $request, $id)
    {
        $validated = $request->validated();
        $priority = $validated['priority'];
        $task = $this->taskService->update($id, ['priority' => $priority]);
        if(!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
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
