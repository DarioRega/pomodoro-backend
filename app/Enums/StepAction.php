<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Start()
 * @method static static Pause()
 * @method static static Resume()
 * @method static static Skip()
 * @method static static Finish()
 */
final class StepAction extends Enum
{
    const Start = 'START';
    const Pause = 'PAUSE';
    const Resume = 'RESUME';
    const Skip = 'SKIP';
    const Finish = 'FINISH';
}
