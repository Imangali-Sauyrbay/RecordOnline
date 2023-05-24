<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GroupValidationRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $pattern = '^\p{Lu}{1,4}-\d{2}-\d{1,2}\p{Ll}{1,4}\d{0,2}$';

        if (!mb_ereg_match($pattern, $value)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.group');
    }
}
