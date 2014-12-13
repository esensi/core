<?php namespace Esensi\Core\Traits;

use InvalidArgumentException;

/**
 * Trait that implements Validator helpers.
 *
 * @package Esensi\Core
 * @author daniel <dalabarge@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
trait ValidatorTrait {

    /**
     * Get the value of a given attribute.
     *
     * @param  string  $attribute
     * @param  array   $data
     * @param  array   $files
     * @return mixed
     * @see Illuminate\Validation\Validator
     */
    protected function getValue($attribute, array $data = [], array $files = [])
    {
        if ( ! is_null($value = array_get($data, $attribute)))
        {
            return $value;
        }
        elseif ( ! is_null($value = array_get($files, $attribute)))
        {
            return $value;
        }
    }

    /**
     * Require a certain number of parameters to be present.
     *
     * @param  int    $count
     * @param  array  $parameters
     * @param  string  $rule
     * @return void
     * @throws InvalidArgumentException
     * @see Illuminate\Validation\Validator
     */
    protected function requireParameterCount($count, $parameters, $rule)
    {
        if (count($parameters) < $count)
        {
            throw new InvalidArgumentException('Validation rule ' . $rule .' requires at least ' . $count . ' parameters.');
        }
    }

}
