<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Pomodoro()
 * @method static static SmallPause()
 * @method static static BigPause()
 */
final class StepType extends Enum
{
    const Pomodoro = 'POMODORO';
    const SmallPause = 'SMALL_PAUSE';
    const BigPause = 'BIG_PAUSE';
}
