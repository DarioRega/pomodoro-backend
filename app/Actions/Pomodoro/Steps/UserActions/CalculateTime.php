<?php

namespace App\Actions\Pomodoro\Steps\UserActions;

use Illuminate\Support\Carbon;

trait CalculateTime
{
    public function calculateEndTime()
    {
        $restingTime = $this->createFromTime($this->step->resting_time);
        $this->step->end_time = now()
            ->addHours($restingTime->hour)
            ->addMinutes($restingTime->minute)
            ->addSeconds($restingTime->second);
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
