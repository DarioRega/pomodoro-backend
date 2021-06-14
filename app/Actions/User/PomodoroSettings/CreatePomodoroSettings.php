<?php

namespace App\Actions\User\PomodoroSettings;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePomodoroSettings
{
    use AsAction;
    use PomodoroSettingHelpers;

    public function handle(User $user, array $values): Model
    {
        $values = array_merge($values, [
            'pomodoro_duration' => $this->getTimeFormattedFromMinutes($values['pomodoro_duration']),
            'small_break_duration' => $this->getTimeFormattedFromMinutes($values['small_break_duration']),
            'big_break_duration' => $this->getTimeFormattedFromMinutes($values['big_break_duration']),
        ]);

        $pomodoroSettings = $user->pomodoroSessionSettings()->create($values);
        $user->userSettings->pomodoro_session_setting_id = $pomodoroSettings['id'];
        $user->userSettings->save();
        return $pomodoroSettings;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable'],
            'pomodoro_duration' => ['required', 'integer', 'between:15,60'],
            'small_break_duration' => ['required', 'integer', 'between:1,15'],
            'big_break_duration' => ['required', 'integer', 'between:15,30'],
            'pomodoro_quantity' => ['required', 'integer', 'between:2,10'],
        ];
    }

    public function asController(ActionRequest $request): Model
    {
        return $this->handle($request->user(), $request->validated());
    }
}
