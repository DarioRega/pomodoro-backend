<?php

namespace Tests\Feature\Tasks;

use App\Actions\Pomodoro\Tasks\CreateTask;
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
    }
}
