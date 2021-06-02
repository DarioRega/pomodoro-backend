<?php

namespace App\Actions\Pomodoro\Steps;

use App\Models\Step;
use App\Models\StepHistory;
use Lorisleiva\Actions\Concerns\AsAction;

class LogAction
{
    use AsAction;

    public function handle(Step $step, \App\Enums\StepAction $action): StepHistory
    {
        return StepHistory::create([
            'step_id' => $step->id,
            'action' => $action,
        ]);
    }
}
