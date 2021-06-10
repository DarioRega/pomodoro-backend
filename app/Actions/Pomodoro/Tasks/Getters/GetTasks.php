<?php

namespace App\Actions\Pomodoro\Tasks\Getters;

use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTasks
{
    use AsAction;

    public function handle(): array|Collection
    {
        return TaskStatus::all();
    }

    public function asController(): Collection|array
    {
        return $this->handle();
    }
}
