<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\PomodoroSession
 *
 * @property string $id
 * @property string|null $goals
 * @property string $pomodoro_duration
 * @property string $small_break_duration
 * @property string $big_break_duration
 * @property int $pomodoro_quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $user_id
 * @property-read Collection|Step[] $steps
 * @property-read int|null $steps_count
 * @property-read User $user
 * @method static Builder|PomodoroSession newModelQuery()
 * @method static Builder|PomodoroSession newQuery()
 * @method static Builder|PomodoroSession query()
 * @method static Builder|PomodoroSession whereBigBreakDuration($value)
 * @method static Builder|PomodoroSession whereCreatedAt($value)
 * @method static Builder|PomodoroSession whereGoals($value)
 * @method static Builder|PomodoroSession whereId($value)
 * @method static Builder|PomodoroSession wherePomodoroDuration($value)
 * @method static Builder|PomodoroSession wherePomodoroQuantity($value)
 * @method static Builder|PomodoroSession whereSmallBreakDuration($value)
 * @method static Builder|PomodoroSession whereUpdatedAt($value)
 * @method static Builder|PomodoroSession whereUserId($value)
 * @mixin Eloquent
 * @method static \Database\Factories\PomodoroSessionFactory factory(...$parameters)
 */
class PomodoroSession extends Model
{
    use HasFactory;
    use Uuids;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'goals',
        'pomodoro_duration',
        'small_break_duration',
        'big_break_duration',
        'pomodoro_quantity',
    ];

    /**
     * Get this sessions steps.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    /**
     * Get the session user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
