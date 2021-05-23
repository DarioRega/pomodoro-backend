<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Step extends Model
{
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
