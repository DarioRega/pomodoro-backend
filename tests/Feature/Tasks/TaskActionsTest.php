<?php

namespace Tests\Feature\Tasks;

use App\Actions\Pomodoro\Tasks\CreateTask;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskActionsTest extends TestCase
{
    use RefreshDatabase;

    public function testCanCreateTaskOnlyWithNameAndStatus()
    {
        $user = User::factory()->create();
        $task = CreateTask::run($user, ['name' => 'Test new task']);
        $this->assertEquals('Test new task', $task->name);
        $this->assertEquals('TODO', $task->taskStatus->name);
        $this->assertNull($task->taskStatus->deadline);
        $this->assertNull($task->taskStatus->description);
    }

    public function testCanGetAllTaskByTaskStatus()
    {
        $user = User::factory()->create();
        CreateTask::run($user, ['name' => 'Test new task']);
        CreateTask::run($user, ['name' => 'Test new task']);
        CreateTask::run($user, ['name' => 'Test new task']);

        $tasks = TaskStatus::whereName('TODO')->first()->tasks;
        $this->assertCount(3, $tasks);
    }
}
