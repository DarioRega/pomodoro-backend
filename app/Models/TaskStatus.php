<?php

namespace App\Models;

use App\Traits\Uuids;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\TaskStatus
 *
 * @property string $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Task[] $tasks
 * @property-read int|null $tasks_count
 * @method static Builder|TaskStatus newModelQuery()
 * @method static Builder|TaskStatus newQuery()
 * @method static Builder|TaskStatus query()
 * @method static Builder|TaskStatus whereCreatedAt($value)
 * @method static Builder|TaskStatus whereId($value)
 * @method static Builder|TaskStatus whereName($value)
 * @method static Builder|TaskStatus whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read \App\Models\User $user
 */
class TaskStatus extends Model
{
    use Uuids;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',

    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
