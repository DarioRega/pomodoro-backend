<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Pending()
 * @method static static InProgress()
 * @method static static Paused()
 * @method static static Done()
 * @method static static Aborted()
 */
final class SessionStatus extends Enum
{
    const Pending = 'PENDING';
    const InProgress = 'IN_PROGRESS';
    const Paused = 'PAUSED';
    const Done = 'DONE';
    const Aborted = 'ABORTED';
}
