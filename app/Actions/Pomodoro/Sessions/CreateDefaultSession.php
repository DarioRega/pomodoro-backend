<?php

namespace App\Actions\Pomodoro\Sessions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateDefaultSession
{
    use AsAction;

    public function handle(string $sessionName): Model
    {
        return Auth::user()->pomodoroSessions()->create([
            'name' => $sessionName,
            'pomodoro_duration' => '00:00:25',
            'small_pause_duration' => '00:00:05',
            'big_pause_duration' => '00:00:15',
            'pomodoro_quantity' => 4
        ]);
    }
}
