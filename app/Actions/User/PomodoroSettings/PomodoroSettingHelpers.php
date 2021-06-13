<?php


namespace App\Actions\User\PomodoroSettings;

trait PomodoroSettingHelpers
{
    public function getTimeFormattedFromMinutes($minutes): string
    {
        if (strlen($minutes) === 1) {
            $minutes = "0$minutes";
        }
        return "00:$minutes:00";
    }
}
