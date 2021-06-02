<?php

namespace App\Actions\Pomodoro\Steps\Create;

use App\Enums\StepType;
use App\Models\PomodoroSession;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateBigBreakStep
{
    use AsAction;

    public function handle(PomodoroSession $session): Model
    {
        $session->steps()->create([
            'type' => StepType::BIG_BREAK(),
            'duration' => $session->big_break_duration,
            'resting_time' => $session->big_break_duration,
        ]);

        return $session->steps->last();
    }
}
