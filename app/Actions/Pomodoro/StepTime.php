<?php

namespace App\Actions\Pomodoro;

use App\Models\Step;
use Illuminate\Support\Carbon;

trait StepTime
{
    public function calculateStepEndTime()
    {
        $restingTime = $this->createFromTime($this->step->resting_time);
        $this->step->end_time = now()
            ->addHours($restingTime->hour)
            ->addMinutes($restingTime->minute)
            ->addSeconds($restingTime->second);
        $this->step->save();
    }

    public function unsetEndTime()
    {
        $this->step->end_time = null;
        $this->step->save();
    }

    public function createFromTime(string $time): bool|\Carbon\Carbon
    {
        return Carbon::createFromFormat('H:i:s', $time);
    }

    public function createFromDateTime(string $datetime): bool|\Carbon\Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime);
    }

    public function calculateRestingTime(Step $step)
    {
        $oldRestingTime = $this->createFromTime($step->resting_time);
        $lastActionTime = $step->actions()->latest()->first()->created_at;
        $timePassed = now()->diffInSeconds($lastActionTime);
        $restingTime = $oldRestingTime->subSeconds($timePassed - 4);

        return $restingTime->format('H:i:s');
    }
}
