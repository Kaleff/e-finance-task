<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $taskService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskService = new TaskService();
    }

    public function test_can_get_tasks_with_pagination(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        Task::factory()->count(25)->create(['project_id' => $project->id]);

        $result = $this->taskService->getTasks([], 20);

        $this->assertCount(20, $result->items());
        $this->assertEquals(25, $result->total());
    }

    public function test_can_filter_tasks_by_status(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        Task::factory()->count(3)->create(['project_id' => $project->id, 'status' => 'todo']);
        Task::factory()->count(2)->create(['project_id' => $project->id, 'status' => 'done']);

        $result = $this->taskService->getTasks(['status' => 'todo'], 10);

        $this->assertCount(3, $result->items());
    }

    public function test_can_filter_tasks_by_priority(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        Task::factory()->count(3)->create(['project_id' => $project->id, 'priority' => 'high']);
        Task::factory()->count(2)->create(['project_id' => $project->id, 'priority' => 'low']);

        $result = $this->taskService->getTasks(['priority' => 'high'], 10);

        $this->assertCount(3, $result->items());
    }

    public function test_can_filter_tasks_by_assignee(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user1->id]);
        Task::factory()->count(3)->create(['project_id' => $project->id, 'assigned_to' => $user1->id]);
        Task::factory()->count(2)->create(['project_id' => $project->id, 'assigned_to' => $user2->id]);

        $result = $this->taskService->getTasks(['assigned_to' => $user1->id], 10);

        $this->assertCount(3, $result->items());
    }

    public function test_can_filter_tasks_by_project(): void
    {
        $user = User::factory()->create();
        $project1 = Project::factory()->create(['owner_id' => $user->id]);
        $project2 = Project::factory()->create(['owner_id' => $user->id]);
        Task::factory()->count(3)->create(['project_id' => $project1->id]);
        Task::factory()->count(2)->create(['project_id' => $project2->id]);

        $result = $this->taskService->getTasks(['project_id' => $project1->id], 10);

        $this->assertCount(3, $result->items());
    }

    public function test_can_get_task_by_id_with_comments_and_assignee(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id, 'assigned_to' => $user->id]);
        TaskComment::factory()->count(5)->create(['task_id' => $task->id, 'user_id' => $user->id]);

        $result = $this->taskService->getTaskById($task->id);

        $this->assertNotNull($result);
        $this->assertEquals($task->id, $result['id']);
        $this->assertCount(5, $result['comments']);
        $this->assertArrayHasKey('comments_pagination', $result);
        $this->assertArrayHasKey('assignee', $result);
        $this->assertEquals($user->id, $result['assignee']['id']);
    }

    public function test_returns_null_for_non_existent_task(): void
    {
        $result = $this->taskService->getTaskById(9999);

        $this->assertNull($result);
    }

    public function test_can_create_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $data = [
            'project_id' => $project->id,
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'todo',
            'priority' => 'medium',
            'estimated_hours' => 5,
        ];

        $task = $this->taskService->createTask($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    public function test_can_update_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id, 'title' => 'Old Title']);

        $result = $this->taskService->update($task->id, ['title' => 'New Title']);

        $this->assertNotNull($result);
        $this->assertEquals('New Title', $result->title);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'New Title']);
    }

    public function test_update_returns_null_for_non_existent_task(): void
    {
        $result = $this->taskService->update(9999, ['title' => 'New Title']);

        $this->assertNull($result);
    }

    public function test_can_delete_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $result = $this->taskService->delete($task->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_delete_returns_false_for_non_existent_task(): void
    {
        $result = $this->taskService->delete(9999);

        $this->assertFalse($result);
    }

    public function test_can_get_task_comments(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);
        TaskComment::factory()->count(5)->create(['task_id' => $task->id, 'user_id' => $user->id]);

        $result = $this->taskService->getTaskComments($task->id);

        $this->assertCount(5, $result);
    }

    public function test_can_create_task_comment(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $comment = $this->taskService->createTaskComment($task->id, 'Test comment', $user->id);

        $this->assertInstanceOf(TaskComment::class, $comment);
        $this->assertEquals('Test comment', $comment->comment);
        $this->assertDatabaseHas('task_comments', ['comment' => 'Test comment']);
    }

    public function test_can_update_task_comment(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);
        $comment = TaskComment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => 'Old comment'
        ]);

        $result = $this->taskService->updateTaskComment($comment->id, 'New comment', $user->id);

        $this->assertNotNull($result);
        $this->assertEquals('New comment', $result->comment);
    }

    public function test_cannot_update_comment_of_another_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user1->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);
        $comment = TaskComment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $user1->id,
            'comment' => 'Old comment'
        ]);

        $result = $this->taskService->updateTaskComment($comment->id, 'New comment', $user2->id);

        $this->assertNull($result);
    }

    public function test_tasks_include_comment_counts(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);
        TaskComment::factory()->count(5)->create(['task_id' => $task->id, 'user_id' => $user->id]);

        $result = $this->taskService->getTasks([], 10);

        $this->assertEquals(5, $result->items()[0]->comments_count);
    }
}
