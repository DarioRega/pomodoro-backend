<?php

namespace App\Actions\Pomodoro\Tasks;

use App\Actions\Pomodoro\ValidatePlan;
use App\Events\Tasks\TaskEvent;
use App\Exceptions\NotSubscribedException;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTask
{
    use AsAction;
    use ValidatePlan;

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

    public function asController(ActionRequest $request): Model|Task|JsonResponse
    {
        try {
            if ($this->userHaveMoreThanFiveTasks($request->user())) {
                $this->userIsSubscribed(__('Update your plan for unlimited tasks'));
            }
        } catch (NotSubscribedException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], '400');
        }

        $task = $this->handle($request->user(), $request->validated());
        broadcast(new TaskEvent($request->user(), $task, 'create'));
        return $task;
    }

    public function userHaveMoreThanFiveTasks(User $user): bool
    {
        return $user->tasks()->count() >= 5;
    }
}
