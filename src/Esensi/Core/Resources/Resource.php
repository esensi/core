<?php namespace Esensi\Core\Resources;

use \LaravelBook\Ardent\Ardent;
use \Illuminate\Support\Facades\Lang;
use \Illuminate\Support\Facades\Config;

/**
 * Core Resource controller as the base for all package Resource controllers
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 */
class Resource extends \EsensiCoreController implements \EsensiCoreResourceInterface {

    /**
     * The package name
     * 
     * @var string
     */
    protected $package = 'core';

    /**
     * The exception to be thrown
     * 
     * @var \Esensi\Core\Exceptions\ResourceException;
     */
    protected $exception = '\EsensiCoreResourceException';

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes to fill on the object
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($attributes)
    {
        $rules = $this->getModel()->rulesForStoring;
        $model = $this->getModel();
        $object = new $model();
        $object->fill($attributes);
        if(!$object->save($rules))
        {
            $this->throwException($object->errors(), $this->language('errors.store'));
        }
        return $object;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id of object
     * @param boolean $withTrashed
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($id, $withTrashed = false)
    {
        $excludeTrashed = !$withTrashed;
        $object = $this->getModel()->newQuery($excludeTrashed)->find($id);
        if(!$object)
        {
            $this->throwException($this->language('errors.show'));
        }
        return $object;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id of object to update
     * @param array $attributes to fill on the object
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, $attributes)
    {
        $object = $this->show($id);

        $rules = $object->rulesForUpdating;
        $object->fill($attributes);
        if(!$object->save($rules))
        {
            $this->throwException($object->errors(), $this->language('errors.update'));
        }
        return $object;
    }

    /**
     * Restore the specified resource after being soft deleted
     *
     * @param int $id of object to restore
     * @return bool
     * 
     */
    public function restore($id)
    {
        $object = $this->show($id, true);
        
        // Sloppy way to get around Ardent $rules validation
        // @todo add a restore() method to Ardent that uses the forceSave method
        $rules = $object::$rules;
        $object::$rules = [];

        // Restore user
        if(!$object->restore())
        {
            $this->throwException($this->language('errors.restore'));
        }
        $object::$rules = $rules;
        return $object;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id of object to remove
     * @param bool $force delete
     * @return bool
     * 
     */
    public function destroy($id, $force = true)
    {
        $object = $this->show($id, true);
        
        // Check if trashing is allowed
        if( method_exists($object, 'isTrashingAllowed') )
        {
            if(!$object->isTrashingAllowed())
            {
                $this->throwException($this->language('errors.trashing'));
            }
        }

        // Delete the user
        $result = ($force || $object->trashed()) ? $object->forceDelete() : $object->delete();
        if($result === false)
        {
            $this->throwException($this->language('errors.destroy'));
        }

        return $result;
    }

    
    /**
     * Throw an exception for this resource
     *
     * @param mixed $messageBag
     * @param string $message
     * @param long $code
     * @param Exception $previous exception
     * @return void
     */
    public function throwException($messageBag, $message = null, $code = 0, Exception $previous = null)
    {
        $exceptionName = $this->exception;
        throw new $exceptionName($messageBag, $message, $code, $previous);
    }

    /**
     * Get a language line
     *
     * @param string $key to language config
     * @param array $replacements in language line
     * @return string
     */
    protected function language($key, $replacements = [])
    {
        $key = str_singular($this->package) . '.' .$key;
        return Lang::has('esensi::' . $key) ? Lang::get('esensi::' . $key, $replacements) : Lang::get($key, $replacements);
    }

}