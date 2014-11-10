<?php namespace Esensi\Core\Validators;

use \Illuminate\Validation\Validator;

/**
 * Validation handlers for comparing values as greater or lesser than other values.
 *
 * @author Daniel LaBarge <dalabarge@emersonmedia.com>
 * @see \Illuminate\Validation\Validator
 * @link http://www.neontsunami.com/post/greater-than-and-less-than-validation-in-laravel-4
 * @link http://daylerees.com/codebright/validation
 */
class ComparisonValidator extends Validator {

    /**
     * Validate that the value is less than another attribute
     *
     * @param string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @param  Validator $validator
     * @return boolean
     */
    public function validateLess($attribute, $value, $parameters, Validator $validator)
    {
        // Require at least one parameter
        $this->requireParameterCount(1, $parameters, 'less');

        $otherField = $parameters[0];
        $otherValue = $validator->getValue($otherField);
        return isset($otherValue) && $value <= $otherValue;
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
    public function replaceLess($message, $attribute, $rule, $parameters)
    {
        return str_replace(':other', str_replace('_', ' ', $parameters[0]), $message);
    }

    /**
     * Validate that the value is greater than another attribute
     *
     * @param string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @param  Validator $validator
     * @return boolean
     */
    public function validateGreater($attribute, $value, $parameters, Validator $validator)
    {
        // Require at least one parameter
        $this->requireParameterCount(1, $parameters, 'greater');

        $otherField = $parameters[0];
        $otherValue = $validator->getValue($otherField);
        return isset($otherValue) && $value >= $otherValue;
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
    public function replaceGreater($message, $attribute, $rule, $parameters)
    {
        return str_replace(':other', str_replace('_', ' ', $parameters[0]), $message);
    }

}
