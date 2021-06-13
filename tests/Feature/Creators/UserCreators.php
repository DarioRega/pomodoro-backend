<?php


namespace Tests\Feature\Creators;

use App\Actions\User\PomodoroSettings\CreatePomodoroSettings;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait UserCreators
{
    public function createUser()
    {
        $user = User::factory()->withUserSettings()->create();
        Sanctum::actingAs($user);
        return $user;
    }

    public function createUserPomodoroSettings()
    {
        $user = $this->createUser();
        return CreatePomodoroSettings::run($user, [
            'pomodoro_duration' => '20',
            'small_break_duration' => '3',
            'big_break_duration' => '20',
            'pomodoro_quantity' => '5',
        ]);
    }

    public function createOtherUserPomodoroSettings()
    {
        $user = $this->createUser();
        $this->createUser();
        return CreatePomodoroSettings::run($user, [
            'pomodoro_duration' => '20',
            'small_break_duration' => '3',
            'big_break_duration' => '20',
            'pomodoro_quantity' => '5',
        ]);
    }
}
