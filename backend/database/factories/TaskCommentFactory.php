<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskComment>
 */
class TaskCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'comment' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the comment is short.
     */
    public function short(): static
    {
        return $this->state(fn (array $attributes) => [
            'comment' => fake()->sentence(),
        ]);
    }

    /**
     * Indicate that the comment is long.
     */
    public function long(): static
    {
        return $this->state(fn (array $attributes) => [
            'comment' => fake()->paragraphs(3, true),
        ]);
    }

    /**
     * Indicate that the comment belongs to a specific task.
     */
    public function forTask(Task $task): static
    {
        return $this->state(fn (array $attributes) => [
            'task_id' => $task->id,
        ]);
    }

    /**
     * Indicate that the comment is by a specific user.
     */
    public function byUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
