<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Start()
 * @method static static Pause()
 * @method static static Continue()
 * @method static static Skip()
 * @method static static Finish()
 */
final class StepAction extends Enum
{
    const Start = 'START';
    const Pause = 'PAUSE';
    const Continue = 'CONTINUE';
    const Skip = 'SKIP';
    const Finish = 'FINISH';
}
