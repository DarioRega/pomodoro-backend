<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static POMODORO()
 * @method static static SMALL_BREAK()
 * @method static static BIG_BREAK()
 */
final class StepType extends Enum
{
    const POMODORO = 'POMODORO';
    const SMALL_BREAK = 'SMALL_BREAK';
    const BIG_BREAK = 'BIG_BREAK';
}
