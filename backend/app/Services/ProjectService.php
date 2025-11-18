<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\ProjectRepository;

class ProjectService
{
    public function getProjects($filters = [], $page = 1)
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

    public function getProjectById($id)
    {
        return Project::with('tasks')->find($id);
    }

    public function createProject(array $data)
    {
        return Project::create($data);
    }

    public function updateProject($id, array $data)
    {
        $project = Project::find($id);
        if ($project) {
            $project->update($data);
            return $project;
        }
        return null;
    }

    public function deleteProject($id)
    {
        $project = Project::find($id);
        if ($project) {
            $project->delete();
            return true;
        }
        return false;
    }
}
