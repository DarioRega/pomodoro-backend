<?php

namespace App\Actions\Pomodoro\Sessions;

use App\Actions\Pomodoro\Sessions\Getters\GetUserCurrentSession;
use App\Exceptions\InvalidStepActionException;
use App\Models\PomodoroSession;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class AbortSession
{
    use AsAction;

    /**
     * @throws InvalidStepActionException
     */
    public function handle(PomodoroSession $session): PomodoroSession
    {
        $session->aborted_at = now();
        $session->save();

        return $session->fresh();
    }

    public function asController(ActionRequest $request): JsonResponse|PomodoroSession|null
    {
        $currentSession = GetUserCurrentSession::run($request->user());

        if ($currentSession === null) {
            return response()->json([
                'message' => __('You have no current session'),
            ], '404');
        }

        return $this->handle($currentSession);
    }
}
