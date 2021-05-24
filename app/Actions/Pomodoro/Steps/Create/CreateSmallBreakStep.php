<?php

namespace App\Actions\Pomodoro\Steps\Create;

use App\Enums\StepType;
use App\Models\PomodoroSession;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSmallBreakStep
{
    use AsAction;

    public function handle(PomodoroSession $session): Model
    {
        $session->steps()->create([
            'type' => StepType::SMALL_BREAK(),
            'duration' => $session->small_break_duration,
            'resting_time' => $session->small_break_duration,
        ]);

        return $session->steps()->latest()->first();
    }
}
