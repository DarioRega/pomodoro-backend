<?php

namespace App\Actions\Pomodoro\Webhooks;

use App\Actions\Pomodoro\Steps\Getters\GetUserCurrentStep;
use App\Actions\Pomodoro\Steps\UserActions\PauseStep;
use App\Enums\StepStatus;
use App\Models\User;
use Spatie\WebhookClient\ProcessWebhookJob;

class Existence extends ProcessWebhookJob
{
    public function handle()
    {
        $event = $this->webhookCall->payload['events'][0];
        $name = $event['name'];
        $channel = $event['channel'];

        if ($name === 'channel_vacated') {
            $user = $this->getUserFromChannel($channel);
            $step = GetUserCurrentStep::run($user);
            $this->pauseCurrentStep($step);
        }
    }

    private function pauseCurrentStep($step)
    {
        if (StepStatus::IN_PROGRESS()->is($step->status)) {
            PauseStep::run($step);
        }
    }

    private function getUserFromChannel($channel): User
    {
        return User::find(str_replace('private-user.', '', $channel));
    }
}
