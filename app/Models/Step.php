<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Step
 *
 * @property int $id
 * @property string $type
 * @property string $duration
 * @property string|null $finished_at
 * @property string|null $skipped_at
 * @property string|null $started_at
 * @property string $resting_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $pomodoro_session_id
 * @property-read Collection|StepAction[] $actions
 * @property-read int|null $actions_count
 * @property-read PomodoroSession $session
 * @method static Builder|Step newModelQuery()
 * @method static Builder|Step newQuery()
 * @method static Builder|Step query()
 * @method static Builder|Step whereCreatedAt($value)
 * @method static Builder|Step whereDuration($value)
 * @method static Builder|Step whereFinishedAt($value)
 * @method static Builder|Step whereId($value)
 * @method static Builder|Step wherePomodoroSessionId($value)
 * @method static Builder|Step whereRestingTime($value)
 * @method static Builder|Step whereSkippedAt($value)
 * @method static Builder|Step whereStartedAt($value)
 * @method static Builder|Step whereType($value)
 * @method static Builder|Step whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Step extends Model
{
    protected $fillable = [
        'type',
        'duration',
        'resting_time',
    ];

    /**
     * Get the sessions for this user.
     */
    public function actions(): HasMany
    {
        return $this->hasMany(StepAction::class);
    }

    /**
     * Get the step's session
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(PomodoroSession::class);
    }
}
