<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PomodoroSession extends Model
{
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
