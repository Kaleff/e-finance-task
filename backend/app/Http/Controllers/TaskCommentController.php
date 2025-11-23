<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index($taskId)
    {
        return response()->json($this->taskService->getTaskComments($taskId));
    }

    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        $taskId = $data['task_id'];

        $comment = $this->taskService->createTaskComment($taskId, $data['comment'], Auth::id());

        return response()->json($comment, 201);
    }

    public function update(UpdateCommentRequest $request, $commentId)
    {
        $data = $request->validated();

        $updated = $this->taskService->updateTaskComment($commentId, $data['comment'], Auth::id());

        if ($updated) {
            return response()->json(['message' => 'Comment updated successfully']);
        } else {
            return response()->json(['message' => 'Comment not found or unauthorized'], 404);
        }
    }
}
