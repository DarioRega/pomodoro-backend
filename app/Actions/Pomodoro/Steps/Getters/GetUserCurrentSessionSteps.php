<?php

namespace App\Actions\Pomodoro\Steps\Getters;

use App\Actions\Pomodoro\Sessions\Getters\GetUserCurrentSession;
use App\Models\User;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserCurrentSessionSteps
{
    use AsAction;

    public function handle(User $user): Collection
    {
        $session = GetUserCurrentSession::run($user);
        if ($session !== null) {
            return $session->steps;
        }
        return collect();
    }

    public function asController(): Collection
    {
        return $this->handle(\Auth::user());
    }
}
