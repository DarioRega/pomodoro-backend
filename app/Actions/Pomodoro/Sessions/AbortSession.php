<?php

namespace App\Actions\Pomodoro\Sessions;

use App\Actions\Pomodoro\Sessions\Getters\GetUserCurrentSession;
use App\Exceptions\InvalidStepActionException;
use App\Models\PomodoroSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
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
        $this->validate($session);

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

        try {
            return $this->handle($currentSession);
        } catch (InvalidStepActionException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], '400');
        } catch (UnauthorizedException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], '403');
        }
    }

    /**
     * @throws InvalidStepActionException
     */
    public function validate(PomodoroSession $session)
    {
        if (Auth::id() !== $session->user_id) {
            throw new UnauthorizedException(__('You are not allowed to abort this session'));
        }
    }
}
