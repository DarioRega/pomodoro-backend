<?php

namespace App\Actions\Pomodoro;

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
}
