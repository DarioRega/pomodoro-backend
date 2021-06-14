<?php

namespace App\Actions\Pomodoro\Tasks;

use App\Events\Tasks\TaskEvent;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTask
{
    use AsAction;

    public function handle(Task $task, array $values): Task
    {
        $task->update($values);
        return Task::with('taskStatus')->get()->find($task->id);
    }


    public function rules(): array
    {
        return [
            'name' => ['nullable', 'min:3'],
            'description' => ['nullable'],
            'deadline' => ['nullable', 'date', 'after:yesterday'],
            'task_status_id' => ['nullable', 'exists:task_statuses,id'],
        ];
    }

    public function asController(ActionRequest $request, Task $task): JsonResponse|Task
    {
        try {
            $this->validate($task);
            $task = $this->handle($task, $request->validated());
            broadcast(new TaskEvent($request->user(), $task, 'update'));
            return $task;
        } catch (UnauthorizedException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], '403');
        }
    }

    public function validate(Task $task)
    {
        if (Auth::id() !== $task->user_id) {
            throw new UnauthorizedException(__('You are not allowed to update this task'));
        }
    }
}
