<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProjectService $projectService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectService = new ProjectService();
    }

    public function test_can_get_projects_with_pagination(): void
    {
        $user = User::factory()->create();
        Project::factory()->count(15)->create(['owner_id' => $user->id]);

        $result = $this->projectService->getProjects([], 10);

        $this->assertCount(10, $result->items());
        $this->assertEquals(15, $result->total());
        $this->assertEquals(2, $result->lastPage());
    }

    public function test_can_filter_projects_by_status(): void
    {
        $user = User::factory()->create();
        Project::factory()->count(3)->create(['owner_id' => $user->id, 'status' => 'planned']);
        Project::factory()->count(2)->create(['owner_id' => $user->id, 'status' => 'in_progress']);

        $result = $this->projectService->getProjects(['status' => 'planned'], 10);

        $this->assertCount(3, $result->items());
        $this->assertEquals('planned', $result->items()[0]->status);
    }

    public function test_can_filter_projects_by_owner(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Project::factory()->count(3)->create(['owner_id' => $user1->id]);
        Project::factory()->count(2)->create(['owner_id' => $user2->id]);

        $result = $this->projectService->getProjects(['owner_id' => $user1->id], 10);

        $this->assertCount(3, $result->items());
    }

    public function test_can_filter_projects_by_deadline_passed(): void
    {
        $user = User::factory()->create();
        Project::factory()->count(2)->create([
            'owner_id' => $user->id,
            'deadline' => now()->subDays(5)
        ]);
        Project::factory()->count(3)->create([
            'owner_id' => $user->id,
            'deadline' => now()->addDays(5)
        ]);

        $result = $this->projectService->getProjects(['deadline_passed' => true], 10);

        $this->assertCount(2, $result->items());
    }

    public function test_can_get_project_by_id_with_tasks(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        Task::factory()->count(5)->create(['project_id' => $project->id]);

        $result = $this->projectService->getProjectById($project->id);

        $this->assertNotNull($result);
        $this->assertEquals($project->id, $result['id']);
        $this->assertCount(5, $result['tasks']);
        $this->assertArrayHasKey('tasks_pagination', $result);
    }

    public function test_returns_null_for_non_existent_project(): void
    {
        $result = $this->projectService->getProjectById(9999);

        $this->assertNull($result);
    }

    public function test_can_create_project(): void
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Test Project',
            'description' => 'Test Description',
            'status' => 'planned',
            'owner_id' => $user->id,
            'deadline' => now()->addDays(30)->format('Y-m-d'),
        ];

        $project = $this->projectService->createProject($data);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('Test Project', $project->name);
        $this->assertDatabaseHas('projects', ['name' => 'Test Project']);
    }

    public function test_can_update_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id, 'name' => 'Old Name']);

        $result = $this->projectService->updateProject($project->id, ['name' => 'New Name']);

        $this->assertNotNull($result);
        $this->assertEquals('New Name', $result->name);
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'New Name']);
    }

    public function test_update_returns_null_for_non_existent_project(): void
    {
        $result = $this->projectService->updateProject(9999, ['name' => 'New Name']);

        $this->assertNull($result);
    }

    public function test_can_delete_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);

        $result = $this->projectService->deleteProject($project->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_delete_returns_false_for_non_existent_project(): void
    {
        $result = $this->projectService->deleteProject(9999);

        $this->assertFalse($result);
    }

    public function test_projects_include_task_counts(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        Task::factory()->count(5)->create(['project_id' => $project->id, 'status' => 'done']);
        Task::factory()->count(3)->create(['project_id' => $project->id, 'status' => 'todo']);

        $result = $this->projectService->getProjects([], 10);

        $this->assertEquals(8, $result->items()[0]->tasks_count);
        $this->assertEquals(5, $result->items()[0]->completed_tasks_count);
    }
}
