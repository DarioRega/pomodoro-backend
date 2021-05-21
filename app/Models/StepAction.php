<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
