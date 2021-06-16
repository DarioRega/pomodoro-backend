<?php

namespace App\Actions\User;

use App\Models\User;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUser
{
    use AsAction;

    public function handle(User $user): User
    {
        $user->load(
            'userSettings',
            'pomodoroSessionSettings',
            'userSettings.pomodoroSessionSetting',
            'localReceipts',
            'subscriptions'
        );
        return $user;
    }

    public function asController(ActionRequest $request): User
    {
        return $this->handle($request->user());
    }
}
