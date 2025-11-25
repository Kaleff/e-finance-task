<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function getProjects($filters = [], $perPage = 10): LengthAwarePaginator
    {
        $query = Project::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['owner_id'])) {
            $query->where('owner_id', $filters['owner_id']);
        }

        if (isset($filters['deadline_passed'])) {
            if ($filters['deadline_passed']) {
                $query->where('deadline', '<', now());
            } else {
                $query->where('deadline', '>=', now());
            }
        }

        return $query->withCount([
            'tasks',
            'tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'done');
            }
        ])->paginate($perPage);
    }

    public function getProjectById($id, $perPage = 100): array|null
    {
        $project = Project::withCount([
            'tasks as total_tasks',
            'tasks as todo_tasks' => function ($query) {
                $query->where('status', 'todo');
            },
            'tasks as in_progress_tasks' => function ($query) {
                $query->where('status', 'in_progress');
            },
            'tasks as completed_tasks' => function ($query) {
                $query->where('status', 'done');
            },
        ])->find($id);

        if (!$project) {
            return null;
        }

        $tasks = $project->tasks()->withCount('comments')->paginate($perPage);

        return [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'status' => $project->status,
            'owner_id' => $project->owner_id,
            'deadline' => $project->deadline,
            'created_at' => $project->created_at,
            'updated_at' => $project->updated_at,
            'tasks' => $tasks->items(),
            'stats' => [
                'total' => $project->total_tasks,
                'todo' => $project->todo_tasks,
                'in_progress' => $project->in_progress_tasks,
                'completed' => $project->completed_tasks,
            ],
            'tasks_pagination' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'from' => $tasks->firstItem(),
                'to' => $tasks->lastItem(),
            ]
        ];
    }

    public function createProject(array $data): Project
    {
        return Project::create($data);
    }

    public function updateProject($id, array $data): Project|null
    {
        $project = Project::find($id);
        if ($project) {
            $project->update($data);
            return $project;
        }
        return null;
    }

    public function deleteProject($id): bool
    {
        $project = Project::find($id);
        if ($project) {
            $project->delete();
            return true;
        }
        return false;
    }
}
