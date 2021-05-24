<?php

namespace App\Actions\Pomodoro\Sessions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateDefaultSession
{
    use AsAction;

    public function handle(array $data = []): Model
    {
        $sessionData = [
            'pomodoro_duration' => '00:00:25',
            'small_break_duration' => '00:00:05',
            'big_break_duration' => '00:00:15',
            'pomodoro_quantity' => 4
        ];

        if (isset($data['goals'])) {
            $sessionData['goals'] = $data['goals'];
        }

        return Auth::user()->pomodoroSessions()->create($sessionData);
    }
}
