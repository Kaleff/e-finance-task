<?php

namespace App\Http\Controllers;

use App\Services\TaskService;

class TaskCommentController extends Controller
{
    public function __construct(private TaskService $taskService) {}
}
