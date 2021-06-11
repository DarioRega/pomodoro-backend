<?php

namespace App\Models;

use App\Enums\SessionStatus;
use App\Enums\StepStatus;
use App\Traits\Uuids;
use Database\Factories\PomodoroSessionFactory;
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
 * @method static PomodoroSessionFactory factory(...$parameters)
 * @method static Builder|PomodoroSession byUser(User $user)
 * @property-read SessionStatus $status
 * @method static Builder|PomodoroSession currentByUser(User $user)
 * @property string|null $aborted_at
 * @method static Builder|PomodoroSession whereAbortedAt($value)
 * @property-read \App\Models\Step|null $current_step
 * @property-read string $end_time
 * @property-read string $resting_time
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

    protected $appends = ['status', 'current_step', 'resting_time', 'end_time'];

    /**
     * Get this sessions steps.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    public function getStatusAttribute(): SessionStatus
    {
        if ($this->aborted_at !== null) {
            return SessionStatus::ABORTED();
        }

        if ($this->hasStepWithStatus(StepStatus::PAUSED())) {
            return SessionStatus::PAUSED();
        }

        if ($this->hasStepWithStatus(StepStatus::IN_PROGRESS())) {
            return SessionStatus::IN_PROGRESS();
        }

        if ($this->hasPendingAndSkippedSteps()) {
            return SessionStatus::IN_PROGRESS();
        }

        if ($this->hasPendingAndDoneSteps()) {
            return SessionStatus::IN_PROGRESS();
        }

        if ($this->hasStepWithStatus(StepStatus::PENDING())) {
            return SessionStatus::PENDING();
        }

        return SessionStatus::DONE();
    }

    public function getCurrentStepAttribute(): ?Step
    {
        $steps = $this->steps;

        $stepInProgress = $steps->filter(function (Step $step) {
            return $step->status == StepStatus::IN_PROGRESS;
        })->first();

        $stepPaused = $steps->filter(function (Step $step) {
            return $step->status == StepStatus::PAUSED;
        })->first();

        $nextPendingStep = $steps->filter(function (Step $step) {
            return $step->status == StepStatus::PENDING;
        })->first();

        if ($stepInProgress !== null) {
            return $stepInProgress;
        }

        if ($stepPaused !== null) {
            return $stepPaused;
        }

        if ($nextPendingStep !== null) {
            return $nextPendingStep;
        }
        return null;
    }

    public function getRestingTimeAttribute(): string
    {
        $restingTime = now()->diffInSeconds($this->calculateEndTime());
        return gmdate('H:i:s', $restingTime);
    }

    public function getEndTimeAttribute(): string
    {
       return $this->calculateEndTime();
    }

    private function calculateEndTime(): string
    {
        $sessionEndTime = now();
        foreach ($this->steps as $step) {
            $stepRestingTime = Carbon::createFromFormat('H:i:s', $step->resting_time);
            $sessionEndTime->addHours($stepRestingTime->hour)
                ->addMinutes($stepRestingTime->minute)
                ->addSeconds($stepRestingTime->second);
        }
        return $sessionEndTime;
    }

    private function hasStepWithStatus(StepStatus $status): bool
    {
        return $this->steps->filter(function (Step $step) use ($status) {
                return $status->is($step->status);
        })->count() > 0;
    }

    private function hasPendingAndDoneSteps(): bool
    {
        return $this->hasStepWithStatus(StepStatus::PENDING()) && $this->hasStepWithStatus(StepStatus::DONE());
    }

    private function hasPendingAndSkippedSteps(): bool
    {
        return $this->hasStepWithStatus(StepStatus::PENDING()) && $this->hasStepWithStatus(StepStatus::SKIPPED());
    }

    /**
     * Get the session user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUser($query, User $user)
    {
        return $query
            ->whereUserId($user->id)
            ->with(['steps', 'steps.actions']);
    }
}
