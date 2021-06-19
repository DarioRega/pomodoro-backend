<?php

namespace App\Actions\User\PomodoroSettings;

use App\Models\PomodoroSessionSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class DeletePomodoroSettings
{
    use AsAction;

    public function handle(PomodoroSessionSetting $sessionSetting): bool
    {
        $this->unlinkPomodoroSessionFromUser($sessionSetting);
        return $sessionSetting->delete();
    }

    public function asController(
        ActionRequest $request,
        PomodoroSessionSetting $pomodoroSessionSettings
    ): bool|JsonResponse {
        try {
            $this->validate($pomodoroSessionSettings);
            return $this->handle($pomodoroSessionSettings);
        } catch (UnauthorizedException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], '403');
        }
    }

    public function validate(PomodoroSessionSetting $sessionSetting)
    {
        if (Auth::id() !== $sessionSetting->user_id) {
            throw new UnauthorizedException(__('You are not allowed to delete this settings'));
        }
    }

    private function unlinkPomodoroSessionFromUser(PomodoroSessionSetting $sessionSetting)
    {
        if (Auth::user()->userSettings->pomodoro_session_setting_id === $sessionSetting->id) {
            Auth::user()->userSettings->pomodoro_session_setting_id = null;
            Auth::user()->userSettings->save();
        }
    }
}
