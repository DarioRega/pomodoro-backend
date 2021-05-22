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

    public function handle(User $user, PomodoroSessionSetting $settings): Model
    {
        return $user->pomodoroSessions()->create([]);
    }

    public function rules(): array
    {
        return [
            'session_name' => ['required', 'min:4'],
            'settings_id' => ['nullable'],
        ];
    }

    public function asController(ActionRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['settings_id'])) {
            return CreateDefaultSession::run($data['session_name']);
        }
    }
}
