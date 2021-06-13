<?php

namespace Database\Factories;

use App\Models\userSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = userSettings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'theme' => 'LIGHT',
            'display_format' => '24H'
        ];
    }
}
