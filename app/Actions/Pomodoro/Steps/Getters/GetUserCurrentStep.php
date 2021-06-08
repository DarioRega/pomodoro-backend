<?php

namespace App\Actions\Pomodoro\Steps\Getters;

use App\Actions\Pomodoro\Sessions\Getters\GetUserCurrentSession;
use App\Models\Step;
use App\Models\User;
use Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserCurrentStep
{
    use AsAction;

    public function handle(User $user): ?Step
    {
        $session = GetUserCurrentSession::run($user);
        return $session->current_step;
    }

    public function asController(): ?Step
    {
        return $this->handle(Auth::user());
    }
}
