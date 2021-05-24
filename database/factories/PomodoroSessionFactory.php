<?php

namespace Database\Factories;

use App\Models\PomodoroSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class PomodoroSessionFactory extends Factory
{
    protected $model = PomodoroSession::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'goals' => $this->faker->text,
            'pomodoro_duration' => $this->fakeMinutes($this->faker->numberBetween(15, 60)),
            'small_break_duration' => $this->fakeMinutes($this->faker->numberBetween(5, 15)),
            'big_break_duration' => $this->fakeMinutes($this->faker->numberBetween(15, 30)),
            'pomodoro_quantity' => $this->faker->numberBetween(4, 8)
        ];
    }

    private function fakeMinutes($minutes): string
    {
        return "00:$minutes";
    }
}
