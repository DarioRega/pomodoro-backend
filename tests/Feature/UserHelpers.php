<?php


namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait UserHelpers
{
    public function createUser()
    {
        Sanctum::actingAs(User::factory()->create());
    }
}
