<?php namespace Esensi\Core\Validators;

use \Carbon\Carbon;
use \Esensi\Core\Traits\ValidatorTrait;
use \Illuminate\Validation\Validator;
use \InvalidArgumentException;

/**
 * Validation handlers for comparing values as greater or lesser than other values.
 *
 * @author Daniel LaBarge <dalabarge@emersonmedia.com>
 * @link http://www.neontsunami.com/post/greater-than-and-less-than-validation-in-laravel-4
 * @link http://daylerees.com/codebright/validation
 */
class ComparisonValidator{

    /**
     * Make this class behave like a Validator.
     *
     * @see \Esensi\Core\Traits\ValidatorTrait
     */
    use ValidatorTrait;

    /**
     * Validate that the value is less than another attribute
     *
     * @param string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @param  \Illuminate\Validation\Validator $validator
     * @return boolean
     */
    public function validateLessThanOther($attribute, $value, $parameters, Validator $validator)
    {
        // Require at least one parameter
        $this->requireParameterCount(1, $parameters, 'less_than_other');

        $otherField = $parameters[0];
        $otherValue = $this->getValue($otherField, $validator->getData(), $validator->getFiles());
        return isset($otherValue) && is_numeric($value) && is_numeric($otherValue) && $value <= $otherValue;
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
    public function replaceLessThanOther($message, $attribute, $rule, $parameters)
    {
        return str_replace(':other', str_replace('_', ' ', $parameters[0]), $message);
    }

    /**
     * Validate that the value is greater than another attribute
     *
     * @param string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @param  \Illuminate\Validation\Validator $validator
     * @return boolean
     */
    public function validateGreaterThanOther($attribute, $value, $parameters, Validator $validator)
    {
        // Require at least one parameter
        $this->requireParameterCount(1, $parameters, 'greater_than_other');

        $otherField = $parameters[0];
        $otherValue = $this->getValue($otherField, $validator->getData(), $validator->getFiles());
        return isset($otherValue) && is_numeric($value) && is_numeric($otherValue) && $value >= $otherValue;
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
    public function replaceGreaterThanOther($message, $attribute, $rule, $parameters)
    {
        return str_replace(':other', str_replace('_', ' ', $parameters[0]), $message);
    }

}
