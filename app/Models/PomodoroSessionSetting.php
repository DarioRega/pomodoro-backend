<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PomodoroSessionSetting extends Model
{
    /**
     * Get this setting owner user
     */
    public function userSettings(): BelongsTo
    {
        return $this->belongsTo(UserSettings::class);
    }
}
