<?php

namespace App\Actions\Pomodoro\Sessions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateCustomSession
{
    use AsAction;
    use PomodoroSettingHelpers;

    public function handle(array $data): Model
    {
        $pomodoroSessionSettings = Auth::user()->userSettings->pomodoroSessionSetting->toArray();
        $sessionData =[
            'pomodoro_duration' =>
                $this->getTimeFormattedFromMinutes($pomodoroSessionSettings['pomodoro_duration']),
            'small_break_duration' =>
                $this->getTimeFormattedFromMinutes($pomodoroSessionSettings['small_break_duration']),
            'big_break_duration' =>
                $this->getTimeFormattedFromMinutes($pomodoroSessionSettings['big_break_duration']),
            'pomodoro_quantity' => $pomodoroSessionSettings['pomodoro_quantity'],
        ];

        if (isset($data['goals'])) {
            $sessionData['goals'] = $data['goals'];
        }

        return Auth::user()->pomodoroSessions()->create($sessionData);
    }
}
