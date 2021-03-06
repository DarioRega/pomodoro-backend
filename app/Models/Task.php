<?php

namespace App\Models;

use App\Traits\Uuids;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Task
 *
 * @property string $id
 * @property string $name
 * @property string|null $deadline
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $task_status_id
 * @property-read TaskStatus $status
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task query()
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereDeadline($value)
 * @method static Builder|Task whereDescription($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereName($value)
 * @method static Builder|Task whereTaskStatusId($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $user_id
 * @method static Builder|Task byUser(User $user)
 * @property-read TaskStatus $taskStatus
 * @method static Builder|Task whereUserId($value)
 * @property-read \App\Models\User $user
 */
class Task extends Model
{
    use Uuids;

    protected $fillable = [
        'name',
        'description',
        'deadline',
        'task_status_id'
    ];

    public function taskStatus(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class);
    }

    /**
     * Get the user tasks.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUser($query, User $user)
    {
        return $query
            ->whereUserId($user->id)
            ->with(['taskStatus']);
    }
}
