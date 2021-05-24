<?php

namespace App\Actions\Pomodoro\Sessions;

use App\Models\PomodoroSessionSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSession
{
    use AsAction;

    public function handle(array $data): Model
    {
        if (isset($data['settings_id'])) {
            // TODO implement create custom session
        }
        if (isset($data['goals'])) {
            return CreateDefaultSession::run($data['goals']);
        }
        return CreateDefaultSession::run();
    }

    public function rules(): array
    {
        return [
            'goals' => ['nullable'],
            'settings_id' => ['nullable'],
        ];
    }

    public function asController(ActionRequest $request): Model
    {
        return $this->handle($request->validated());
    }
}
