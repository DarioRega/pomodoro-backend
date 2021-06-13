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

    public function createManyTasks()
    {
        $user = $this->createUser();
        for($x = 0; $x < 4; ++$x) {
            CreateTask::run($user, ['name' => 'Test tasks n°'.$x]);
        }
    }

    public function createOtherUserTask()
    {
        $user = $this->createUser();
        $this->createUser();
        return CreateTask::run($user, ['name' => 'Test tasks']);
    }
}
