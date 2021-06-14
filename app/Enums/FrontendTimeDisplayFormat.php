<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static TWENTY_FOUR_HOURS()
 * @method static static TWELVE_HOURS()
 */
final class FrontendTimeDisplayFormat extends Enum
{
    const TWENTY_FOUR_HOURS = '24H';
    const TWELVE_HOURS = '12H';
}
