<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $projectService) {}

    public function index(IndexProjectRequest $request)
    {
        $filters = $request->validated();
        $perPage = $filters['per_page'] ?? 10;
        unset($filters['per_page']); // Remove per_page from filters
        $filters['deadline_passed'] = isset($filters['deadline_passed']) ? filter_var($filters['deadline_passed'], FILTER_VALIDATE_BOOLEAN) : null;

        $projects = $this->projectService->getProjects(filters: $filters, perPage: $perPage);
        return response()->json($projects);
    }

    public function show($id)
    {
        $project = $this->projectService->getProjectById($id);
        return response()->json($project);
    }

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        $validated['owner_id'] = auth('sanctum')->id();
        if($validated) {
            $this->projectService->createProject($validated);
        }
        return response()->json(['message' => 'Project created successfully'], 201);
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        $validated = $request->validated();
        if($validated) {
            $this->projectService->updateProject($id, $validated);
        }
        return response()->json(['message' => 'Project updated successfully']);
    }

    public function destroy($id)
    {
        $deleted = $this->projectService->deleteProject($id);
        if ($deleted) {
            return response()->json(['message' => 'Project deleted successfully']);
        } else {
            return response()->json(['message' => 'Project not found'], 404);
        }
    }
}
