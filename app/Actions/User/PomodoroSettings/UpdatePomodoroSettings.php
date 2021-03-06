<?php

namespace App\Actions\User\PomodoroSettings;

use App\Models\PomodoroSessionSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdatePomodoroSettings
{
    use AsAction;

    public function handle(PomodoroSessionSetting $sessionSetting, array $values): Model
    {
        $sessionSetting->update($values);
        return PomodoroSessionSetting::find($sessionSetting->id);
    }


    public function rules(): array
    {
        return [
            'name' => ['nullable'],
            'pomodoro_duration' => ['nullable', 'integer', 'between:15,60'],
            'small_break_duration' => ['nullable', 'integer', 'between:1,15'],
            'big_break_duration' => ['nullable', 'integer', 'between:15,30'],
            'pomodoro_quantity' => ['nullable', 'integer', 'between:2,10'],
        ];
    }

    public function asController(
        ActionRequest $request,
        PomodoroSessionSetting $pomodoroSessionSettings
    ): Model|JsonResponse {
        try {
            $this->validate($pomodoroSessionSettings);
            return $this->handle($pomodoroSessionSettings, $request->validated());
        } catch (UnauthorizedException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], '403');
        }
    }

    public function validate(PomodoroSessionSetting $sessionSetting)
    {
        if (Auth::id() !== $sessionSetting->user_id) {
            throw new UnauthorizedException(__('You are not allowed to update this settings'));
        }
    }
}
