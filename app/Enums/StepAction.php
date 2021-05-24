<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static START()
 * @method static static PAUSE()
 * @method static static RESUME()
 * @method static static SKIP()
 * @method static static FINISH()
 */
final class StepAction extends Enum
{
    const START = 'START';
    const PAUSE = 'PAUSE';
    const RESUME = 'RESUME';
    const SKIP = 'SKIP';
    const FINISH = 'FINISH';
}
