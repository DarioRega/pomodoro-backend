<?php

namespace App\Actions\Pomodoro\Steps\Getters;

use App\Actions\Pomodoro\Sessions\Getters\GetUserCurrentSession;
use App\Models\Step;
use App\Models\User;
use Auth;
use Illuminate\Http\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserCurrentStep
{
    use AsAction;

    public function handle(User $user): ?Step
    {
        $session = GetUserCurrentSession::run($user);

        if (isset($session['current_step'])) {
            return $session['current_step'];
        }
        return null;
    }

    public function asController(): Step|Response
    {
        $step = $this->handle(Auth::user());

        if ($step === null) {
            return response()->noContent();
        }

        return $step;
    }
}
