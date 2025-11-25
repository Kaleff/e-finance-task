<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function getTasks($filters = [], $perPage = 20): LengthAwarePaginator
    {
        $query = Task::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (isset($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        return $query->withCount('comments')->paginate($perPage);
    }

    public function getTaskById($id, $perPage = 10): array|null
    {
        $task = Task::find($id);

        if(!$task) {
            return null;
        }

        $comments = $task->comments()->paginate($perPage);
        $assignee = $task->assignee()->first();

        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'project_id' => $task->project_id,
            'status' => $task->status,
            'assigned_to' => $task->assigned_to,
            'priority' => $task->priority,
            'estimated_hours' => $task->estimated_hours,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
            'comments' => $comments->items(),
            'comments_pagination' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
            'assignee' => [
                'id' => $assignee?->id,
                'name' => $assignee?->name,
                'email' => $assignee?->email,
            ],
        ];
    }

    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    public function update($id, array $data): Task|null
    {
        $task = Task::find($id);
        if ($task) {
            $task->update($data);
            return $task;
        }
        return null;
    }

    public function delete($id): bool
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
            return true;
        }
        return false;
    }

    public function getTaskComments($taskId): Collection
    {
        return TaskComment::where('task_id', $taskId)->get();

    }

    public function createTaskComment($taskId, $commentText, $userId): TaskComment
    {
        $comment = TaskComment::create([
            'task_id' => $taskId,
            'comment' => $commentText,
            'user_id' => $userId,
        ]);
        return $comment;
    }

    public function updateTaskComment($commentId, $commentText, $userId): TaskComment|null
    {
        $comment = TaskComment::find($commentId);
        if ($comment && $comment->user_id == $userId) {
            $comment->update(['comment' => $commentText]);
            return $comment->fresh();
        }
        return null;
    }
}
