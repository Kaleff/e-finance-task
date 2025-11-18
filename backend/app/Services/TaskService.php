<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskComment;

class TaskService
{
    public function getTasks($filters = [], $page = 1)
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

        $query->skip(($page - 1) * 10)->take(10);

        return $query->with('comments')->get();
    }

    public function getTaskById($id)
    {
        return Task::with('comments')->find($id);
    }

    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function update($id, array $data)
    {
        $task = Task::find($id);
        if ($task) {
            $task->update($data);
            return $task;
        }
        return null;
    }

    public function delete($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->delete();
            return true;
        }
        return false;
    }

    public function getTaskComments($taskId)
    {
        $comments = TaskComment::where('task_id', $taskId)->get();
        return $comments;
    }

    public function createTaskComment($taskId, $commentText, $userId)
    {
        $comment = TaskComment::create([
            'task_id' => $taskId,
            'comment' => $commentText,
            'user_id' => $userId,
        ]);
        return $comment;
    }

    public function updateTaskComment($commentId, $commentText, $userId)
    {
        $comment = TaskComment::find($commentId);
        if ($comment && $comment->user_id == $userId) {
            $comment->update(['comment' => $commentText]);
            return true;
        }
        return false;
    }
}
