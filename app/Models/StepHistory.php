<?php

namespace App\Models;

use App\Traits\Uuids;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\StepAction
 *
 * @property string $id
 * @property string $action
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $step_id
 * @property-read Step $step
 * @method static Builder|StepHistory newModelQuery()
 * @method static Builder|StepHistory newQuery()
 * @method static Builder|StepHistory query()
 * @method static Builder|StepHistory whereAction($value)
 * @method static Builder|StepHistory whereCreatedAt($value)
 * @method static Builder|StepHistory whereId($value)
 * @method static Builder|StepHistory whereStepId($value)
 * @method static Builder|StepHistory whereUpdatedAt($value)
 * @mixin Eloquent
 */
class StepHistory extends Model
{
    use Uuids;
    protected $fillable = [
        'action',
        'step_id',
    ];

    /**
     * Get this action's step.
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(Step::class);
    }
}
