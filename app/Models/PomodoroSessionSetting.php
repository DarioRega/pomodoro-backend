<?php

namespace App\Models;

use App\Traits\Uuids;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\PomodoroSessionSetting
 *
 * @property string $id
 * @property string|null $name
 * @property string $pomodoro_duration
 * @property string $small_break_duration
 * @property string $big_break_duration
 * @property int $pomodoro_quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $user_setting_id
 * @property-read UserSettings $userSettings
 * @method static Builder|PomodoroSessionSetting newModelQuery()
 * @method static Builder|PomodoroSessionSetting newQuery()
 * @method static Builder|PomodoroSessionSetting query()
 * @method static Builder|PomodoroSessionSetting whereBigBreakDuration($value)
 * @method static Builder|PomodoroSessionSetting whereCreatedAt($value)
 * @method static Builder|PomodoroSessionSetting whereId($value)
 * @method static Builder|PomodoroSessionSetting whereName($value)
 * @method static Builder|PomodoroSessionSetting wherePomodoroDuration($value)
 * @method static Builder|PomodoroSessionSetting wherePomodoroQuantity($value)
 * @method static Builder|PomodoroSessionSetting whereSmallBreakDuration($value)
 * @method static Builder|PomodoroSessionSetting whereUpdatedAt($value)
 * @method static Builder|PomodoroSessionSetting whereUserSettingId($value)
 * @mixin Eloquent
 * @property string $user_id
 * @property-read \App\Models\User $user
 * @method static Builder|PomodoroSessionSetting whereUserId($value)
 */
class PomodoroSessionSetting extends Model
{
    use Uuids;

    protected $fillable = [
        'name',
        'pomodoro_duration',
        'small_break_duration',
        'big_break_duration',
        'pomodoro_quantity'
    ];

    /**
     * Get this setting owner user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
