<?php

namespace App\Actions\Pomodoro\Sessions;

use App\Actions\Pomodoro\Sessions\Getters\GetUserCurrentSession;
use App\Actions\Pomodoro\Steps\UserActions\StartStep;
use App\Exceptions\InvalidStepActionException;
use App\Models\PomodoroSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class StartSession
{
    use AsAction;

    /**
     * @throws InvalidStepActionException
     */
    public function handle(PomodoroSession $session): PomodoroSession
    {
        StartStep::run($session->steps->first());
        return PomodoroSession::whereId($session->id)->with(['steps', 'steps.actions'])->first();
    }

    public function asController(ActionRequest $request, PomodoroSession $session): JsonResponse|PomodoroSession
    {
        try {
            $this->validate($session);
            return $this->handle($session);
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
            throw new UnauthorizedException(__('You are not allowed to start this session'));
        }

        if (GetUserCurrentSession::run(Auth::user()) !== null) {
            throw new InvalidStepActionException(__('You cannot have 2 session running'));
        }
    }
}
