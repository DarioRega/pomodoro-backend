<?php

namespace App\Actions\Pomodoro\Tasks\Getters;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTasks
{
    use AsAction;

    public function handle(User $user): array|Collection
    {
        return Task::byUser($user)->get();
    }

    public function asController(): Collection|array
    {
        return $this->handle(\Auth::user());
    }
}
