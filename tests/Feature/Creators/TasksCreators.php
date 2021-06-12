<?php


namespace Tests\Feature\Creators;

use App\Actions\Pomodoro\Tasks\CreateTask;
use Illuminate\Support\Collection;

trait TasksCreators
{
    use UserCreators;

    public function createTask()
    {
        $user = $this->createUser();
        return CreateTask::run($user, ['name' => 'Test tasks']);
    }

    public function createManyTasks(): Collection
    {
        $user = $this->createUser();
        $tasksCollection = collect();
            for($x = 1; $x < 4, $x++;) {
                $task = CreateTask::run($user, ['name' => 'Test tasks n°'.$x]);
                $tasksCollection->add($task);
            }
        return $tasksCollection;
    }

    public function createOtherUserTask()
    {
        $user = $this->createUser();
        $this->createUser();
        return CreateTask::run($user, ['name' => 'Test tasks']);
    }
}
