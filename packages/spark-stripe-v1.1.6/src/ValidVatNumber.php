<?php

namespace Spark;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Mpociot\VatCalculator\VatCalculator;

class ValidVatNumber implements Rule
{
    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        try {
            return (new VatCalculator)->isValidVATNumber($value);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return __('The provided VAT number is invalid.');
    }
}
