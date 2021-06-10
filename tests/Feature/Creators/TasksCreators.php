<?php


namespace Tests\Feature\Creators;

use App\Actions\Pomodoro\Tasks\CreateTask;

trait TasksCreators
{
    use UserCreators;

    public function createTask()
    {
        $user = $this->createUser();
        return CreateTask::run($user, ['name' => 'Test tasks']);
    }

    public function createOtherUserTask()
    {
        $user = $this->createUser();
        $this->createUser();
        return CreateTask::run($user, ['name' => 'Test tasks']);
    }
}
