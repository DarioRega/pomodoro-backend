<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static IN_PROGRESS()
 * @method static static PAUSED()
 * @method static static DONE()
 * @method static static SKIPPED()
 */
final class StepStatus extends Enum
{
    const PENDING = 'PENDING';
    const IN_PROGRESS = 'IN_PROGRESS';
    const PAUSED = 'PAUSED';
    const DONE = 'DONE';
    const SKIPPED = 'SKIPPED';
}
