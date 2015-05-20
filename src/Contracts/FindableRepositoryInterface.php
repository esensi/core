<?php namespace Esensi\Core\Contracts;

/**
 * Findable Repository Interface
 *
 * @package Esensi\Core
 * @author daniel <daniel@emersonmedia.com>
 * @copyright 2015 Emerson Media LP
 * @license https://github.com/esensi/core/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 */
interface FindableRepositoryInterface{

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function all();

    /**
     * Display the specified resource.
     *
     * @param integer $id of resource
     * @return Esensi\Core\Models\Model
     */
    public function find($id);

    /**
     * Retrieve the specified resource from trash.
     *
     * @param integer $id of resource
     * @return Esensi\Core\Models\Model
     */
    public function findInTrash($id);

    /**
     * Display the specified resource that matches the attribute.
     *
     * @param string $attribute to find by
     * @param string $value to match attribute against
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return Esensi\Core\Models\Model
     */
    public function findBy($attribute, $value);

    /**
     * Display the specified resource that matches the attribute.
     *
     * @param string $attribute to find by
     * @param array $values to match attribute against
     * @param boolean $inTrash (optional)
     * @throws Esensi\Core\Exceptions\RepositoryException
     * @return array
     */
    public function findIn($attribute, array $values = [], $inTrash = false);

    /**
     * Display the specified resource with loaded relationships.
     *
     * @param integer $id of resource
     * @param array $relationship to load on resource
     * @return Esensi\Core\Models\Model
     */
    public function findWithRelated($id, array $relationship);

}
