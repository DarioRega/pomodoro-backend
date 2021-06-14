<?php

namespace App\Actions\User\Settings;

use App\Enums\FrontendTimeDisplayFormat;
use App\Enums\FrontendTheme;
use App\Models\User;
use App\Models\UserSettings;
use BenSampo\Enum\Rules\EnumValue;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUserSettings
{
    use AsAction;

    public function handle(User $user, array $values): UserSettings
    {
        $user->userSettings()->update($values);
        return UserSettings::whereUserId($user->id)->first();
    }

    public function rules(): array
    {
        return [
            'theme' => ['nullable', new EnumValue(FrontendTheme::class)],
            'time_display_format' => ['nullable', new EnumValue(FrontendTimeDisplayFormat::class)],
        ];
    }

    public function asController(ActionRequest $request): UserSettings
    {
         return $this->handle($request->user(), $request->validated());
    }
}
