<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Pomodoro()
 * @method static static SmallBreak()
 * @method static static BigBreak()
 */
final class StepType extends Enum
{
    const Pomodoro = 'POMODORO';
    const SmallBreak = 'SMALL_BREAK';
    const BigBreak = 'BIG_BREAK';
}
