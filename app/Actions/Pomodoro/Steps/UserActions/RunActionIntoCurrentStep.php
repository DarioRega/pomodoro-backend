<?php

namespace App\Actions\Pomodoro\Steps\UserActions;

use App\Actions\Pomodoro\Steps\Getters\GetUserCurrentStep;
use App\Enums\StepAction;
use App\Exceptions\InvalidStepActionException;
use App\Models\Step;
use App\Models\User;
use Auth;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RunActionIntoCurrentStep
{
    use AsAction;

    /**
     * @throws InvalidStepActionException
     */
    public function handle(User $user, $data): Step
    {
        ['type' => $type] = $data;

        $step = GetUserCurrentStep::run($user);

        if (StepAction::START()->is($type)) {
            return StartStep::run($step);
        }

        if (StepAction::PAUSE()->is($type)) {
            if (!isset($data['resting_time'])) {
                throw new InvalidStepActionException(__('resting_time is mandatory'));
            }
            return PauseStep::run($step, $data['resting_time']);
        }

        if (StepAction::RESUME()->is($type)) {
            return ResumeStep::run($step);
        }

        if (StepAction::SKIP()->is($type)) {
            return SkipStep::run($step);
        }

        if (StepAction::FINISH()->is($type)) {
            return FinishStep::run($step);
        }

        throw new InvalidStepActionException("Invalid step action type: $type");
    }

    public function rules(): array
    {
        return [
            'type' => ['required'],
            'resting_time' => ['nullable', 'date_format:H:i:s']
        ];
    }

    public function asController(ActionRequest $request): Step|JsonResponse
    {
        try {
            return $this->handle(Auth::user(), $request->validated());
        } catch (InvalidStepActionException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], '400');
        }
    }
}
