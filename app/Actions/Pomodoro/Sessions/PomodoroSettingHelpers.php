<?php


namespace App\Actions\Pomodoro\Sessions;

trait PomodoroSettingHelpers
{
    public function getTimeFormattedFromMinutes($minutes): string
    {
        if ($minutes === '60') {
            return "01:00:00";
        }

        if (strlen($minutes) === 1) {
            $minutes = "0$minutes";
        }
        return "00:$minutes:00";
    }
}
