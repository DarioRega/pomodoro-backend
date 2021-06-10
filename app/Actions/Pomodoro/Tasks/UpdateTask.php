<?php

namespace App\Actions\Pomodoro\Tasks;

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
        return Task::find($task->id);
    }


    public function rules(): array
    {
        return [
            'name' => ['nullable'],
            'description' => ['nullable'],
            'deadline' => ['nullable', 'date', 'after:today'],
            'task_status_id' => ['nullable', 'exists:task_statuses,id'],
        ];
    }

    public function asController(ActionRequest $request, Task $task): JsonResponse|Task
    {
        try {
            $this->validate($task);
            return $this->handle($task, $request->validated());
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
