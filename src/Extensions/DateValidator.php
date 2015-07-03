<?php

namespace Esensi\Core\Extensions;

use Carbon\Carbon;
use Esensi\Core\Traits\ValidatorTrait;
use Illuminate\Validation\Validator;
use InvalidArgumentException;

/**
 * Validation handlers for comparing dates against other attributes.
 *
 * @package Esensi\Core
 * @author Daniel LaBarge <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 * @link http://www.neontsunami.com/post/greater-than-and-less-than-validation-in-laravel-4
 * @link http://daylerees.com/codebright/validation
 */
class DateValidator
{
    /**
     * Make this class behave like a Validator.
     *
     * @see Esensi\Core\Traits\ValidatorTrait
     */
    use ValidatorTrait;

    /**
     * Validate that the date is before another attribute date
     *
     * @param string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @param  Illuminate\Validation\Validator $validator
     * @return boolean
     */
    public function validateBeforeOther($attribute, $value, $parameters, Validator $validator)
    {
        // Require at least one parameter
        $this->requireParameterCount(1, $parameters, 'before_other');

        // Get the other value
        $otherField = $parameters[0];
        $otherValue = $this->getValue($otherField, $validator->getData(), $validator->getFiles());

        // Convert the values to dates if not already
        $value      = $this->asDateFromValue($value);
        $otherValue = $this->asDateFromValue($otherValue);

        // Compare that the date is before the other date
        return isset($value) && isset($otherValue) && $value <= $otherValue;
    }

    /**
     * Replace the :other placeholder with the other attribute name
     *
     * @param string $message
     * @param string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    public function replaceBeforeOther($message, $attribute, $rule, $parameters)
    {
        return str_replace(':other', str_replace('_', ' ', $parameters[0]), $message);
    }

    /**
     * Validate that the date is after another attribute date
     *
     * @param string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @param  Illuminate\Validation\Validator $validator
     * @return boolean
     */
    public function validateAfterOther($attribute, $value, $parameters, Validator $validator)
    {
        // Require at least one parameter
        $this->requireParameterCount(1, $parameters, 'after_other');

        // Get the other value
        $otherField = $parameters[0];
        $otherValue = $this->getValue($otherField, $validator->getData(), $validator->getFiles());

        // Convert the values to dates if not already
        $value      = $this->asDateFromValue($value);
        $otherValue = $this->asDateFromValue($otherValue);

        // Compare that the date is after the other date
        return isset($value) && isset($otherValue) && $value >= $otherValue;
    }

    /**
     * Replace the :other placeholder with the other attribute name
     *
     * @param string $message
     * @param string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return string
     */
    public function replaceAfterOther($message, $attribute, $rule, $parameters)
    {
        return str_replace(':other', str_replace('_', ' ', $parameters[0]), $message);
    }

    /**
     * Create a Carbon date from a value that should be in the format.
     *
     * @param  mixed $value of date
     * @param  string $format date should be in
     * @return Carbon\Carbon|null
     */
    protected function asDateFromValue($value = null, $format = 'm/d/Y')
    {
        if( $value instanceof Carbon )
        {
            return $value;
        }

        try{
            return Carbon::createFromFormat($format, $value)->startOfDay();
        }
        catch(InvalidArgumentException $e)
        {

        }

        try{
            $date = new Carbon($value);
            return $date->startOfDay();
        }
        catch(InvalidArgumentException $e)
        {
            return null;
        }
    }

}
