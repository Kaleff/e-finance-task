<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService
{
    public function getAllTasks()
    {

    }

    public function getTaskById($id)
    {

    }

    public function createTask(array $data)
    {

    }

    public function update($id, array $data)
    {

    }

    public function delete($id)
    {

    }

    public function getTaskComments($taskId)
    {
        // Logic to retrieve comments for the given task ID
    }

    public function createTaskComment($taskId, $commentText, $userId)
    {
        // Logic to add a comment to the given task ID by the specified user
    }

    public function updateTaskComment($commentId, $commentText, $userId)
    {
        // Logic to update the comment with the given comment ID if it belongs to the specified user
    }
}
