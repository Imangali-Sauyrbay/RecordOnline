<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NameValidationRule implements Rule
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
        $pattern = '^(?:\p{Lu}\p{Ll}{1,}\s){2}\p{Lu}\p{Ll}{1,}$';

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
        return trans('validation.full_name');
    }
}
