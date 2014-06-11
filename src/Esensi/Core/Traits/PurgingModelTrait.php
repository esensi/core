<?php namespace Esensi\Core\Traits;

/**
 * Trait that implements the PurgingModelInterface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\PurgingModelInterface
 */
trait PurgingModelTrait{

    /**
     * The attributes that are purgeable
     *
     * @var array
     */
    protected $purgeable = [];

    /**
     * Whether the model is purging or not
     *
     * @var boolean
     */
    protected $purging = true;

    /**
     * Get the purgeable attributes
     *
     * @return array
     */
    public function getPurgeable()
    {
        return $this->purgeable ?: [];
    }

    /**
     * Set the purgeable attributes
     *
     * @param  array $attributes to encrypt
     * @return void
     */
    public function setPurgeable( array $attributes )
    {
        $this->purgeable = $attributes;
    }

    /**
     * Returns whether or not the model will purge
     * attributes before saving
     *
     * @return boolean
     */
    public function getPurging()
    {
        return $this->purging;
    }

    /**
     * Set whether or not the model will purge attributes
     * before saving
     *
     * @param  boolean
     * @return void
     */
    public function setPurging( $value )
    {
        $this->purging = (bool) $value;
    }

    /**
     * Returns whether the attribute is purgeable
     *
     * @param string $attribute name
     * @return boolean
     */
    public function isPurgeable( $attribute )
    {
        return in_array( $attribute, $this->getPurgeable() );
    }

    /**
     * Unset attributes that should be purged
     *
     * @return void
     */
    function purgeAttributes()
    {
        // Get the attribute keys
        $keys = array_keys( $this->attributes );

        // Filter out keys that should purged
        $attributes = array_filter( $keys,
            function( $key )
            {
                // Remove attributes containing _confirmation suffix
                if ( Str::endsWith( $key, '_confirmation' ) )
                {
                    return false;
                }

                // Remove attributes containing _ prefix
                if ( Str::startsWith( $key, '_' ) )
                {
                    return false;
                }

                // Remove attributes that should be purged
                if ( in_array( $key, $this->getPurgeable() ) )
                {
                    return false;
                }

                return true;
            });

        // Keep only the attributes that were not purged
        $this->attributes = array_intersect( $this->attributes, array_flip( $attributes ) );
    }

}
