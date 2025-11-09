<?php

namespace App\Http\Controllers;

use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}
}
