<?php


namespace Tests\Feature\Creators;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait UserCreators
{
    public function createUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }
}
