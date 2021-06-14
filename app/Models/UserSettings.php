<?php

namespace App\Models;

use App\Traits\Uuids;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserSettings
 *
 * @property string $id
 * @property string $theme
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|PomodoroSessionSetting[] $pomodoroSettings
 * @property-read int|null $pomodoro_settings_count
 * @property-read User $user
 * @method static Builder|UserSettings newModelQuery()
 * @method static Builder|UserSettings newQuery()
 * @method static Builder|UserSettings query()
 * @method static Builder|UserSettings whereCreatedAt($value)
 * @method static Builder|UserSettings whereId($value)
 * @method static Builder|UserSettings whereTheme($value)
 * @method static Builder|UserSettings whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $time_display_format
 * @property string $user_id
 * @method static Builder|UserSettings whereDisplayFormat($value)
 * @method static Builder|UserSettings whereUserId($value)
 * @property string $pomodoro_session_setting_id
 * @property-read \App\Models\PomodoroSessionSetting|null $pomodoroSessionSetting
 * @method static \Database\Factories\UserSettingsFactory factory(...$parameters)
 * @method static Builder|UserSettings wherePomodoroSessionSettingId($value)
 */
class UserSettings extends Model
{
    use Uuids;
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pomodoroSessionSetting(): BelongsTo
    {
        return $this->belongsTo(PomodoroSessionSetting::class);
    }
}
