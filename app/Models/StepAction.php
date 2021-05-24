<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\StepAction
 *
 * @property int $id
 * @property string $action
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $step_id
 * @property-read Step $step
 * @method static Builder|StepAction newModelQuery()
 * @method static Builder|StepAction newQuery()
 * @method static Builder|StepAction query()
 * @method static Builder|StepAction whereAction($value)
 * @method static Builder|StepAction whereCreatedAt($value)
 * @method static Builder|StepAction whereId($value)
 * @method static Builder|StepAction whereStepId($value)
 * @method static Builder|StepAction whereUpdatedAt($value)
 * @mixin Eloquent
 */
class StepAction extends Model
{
    /**
     * Get this action's step.
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(Step::class);
    }
}
