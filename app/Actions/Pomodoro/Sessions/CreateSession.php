<?php

namespace App\Actions\Pomodoro\Sessions;

use App\Actions\Pomodoro\Steps\Create\CreateSessionSteps;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSession
{
    use AsAction;

    public function handle(array $data): Model
    {
        if (Auth::user()->userSettings->pomodoro_session_setting_id !== null) {
            $session = CreateCustomSession::run($data);
        } else {
            $session = CreateDefaultSession::run($data);
        }

        CreateSessionSteps::run($session);
        return $session->with('steps')->latest()->first();
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
