<?php

namespace App\Actions\Pomodoro\Steps\Create;

use App\Enums\StepType;
use App\Models\PomodoroSession;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePomodoroStep
{
    use AsAction;

    public function handle(PomodoroSession $session): Model
    {
        $session->steps()->create([
            'type' => StepType::POMODORO(),
            'duration' => $session->pomodoro_duration,
            'resting_time' => $session->pomodoro_duration,
        ]);
        $session->save();

        return $session->steps->last();
    }
}
