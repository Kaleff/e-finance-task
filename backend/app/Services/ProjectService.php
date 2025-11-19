<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    public function getProjects($filters = [], $page = 1): Collection
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

        $query->skip(($page - 1) * 10)->take(10);

        return $query->with('tasks')->get();
    }

    public function getProjectById($id): Project|null
    {
        return Project::with('tasks')->find($id);
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
