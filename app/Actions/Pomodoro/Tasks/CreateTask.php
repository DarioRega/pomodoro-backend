<?php

namespace App\Actions\Pomodoro\Tasks;

use App\Events\Tasks\TaskEvent;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTask
{
    use AsAction;

    public function handle(User $user, array $values): Model|Task
    {
        $status = TaskStatus::whereName('TODO')->first();
        $values = array_merge(['task_status_id' => $status->id], $values);
        return $user->tasks()->create($values)
            ->load('taskStatus');
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3'],
            'description' => ['nullable'],
            'deadline' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function asController(ActionRequest $request): Model|Task
    {
         $task = $this->handle($request->user(), $request->validated());
         broadcast(new TaskEvent($request->user(), $task, 'create'));
         return $task;
    }
}
