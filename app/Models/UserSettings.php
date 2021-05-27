<?php

namespace App\Models;

use App\Traits\Uuids;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 */
class UserSettings extends Model
{
    use Uuids;
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pomodoroSettings(): HasMany
    {
        return $this->hasMany(PomodoroSessionSetting::class);
    }
}
