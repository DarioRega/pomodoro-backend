<?php

namespace Tests\Feature;

use App\Actions\Pomodoro\Steps\LogAction;
use App\Enums\StepAction;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogActionsTest extends TestCase
{
    use RefreshDatabase;
    use SessionsAndSteps;

    /**
     * @dataProvider provider
     */
    public function testLogAction(StepAction $stepAction)
    {
        $session = $this->createSessionWithSteps();
        $step = $session->steps()->first();
        $action = LogAction::run($step, $stepAction);

        $this->assertEquals($step->id, $action->step->id);
        $this->assertEquals($stepAction, $action->action);
    }

    public function provider(): array
    {
        return [
            'Start' => [StepAction::START()],
            'Skip' => [StepAction::SKIP()],
            'Resume' => [StepAction::RESUME()],
            'Pause' => [StepAction::PAUSE()],
            'Finish' => [StepAction::FINISH()],
        ];
    }
}
