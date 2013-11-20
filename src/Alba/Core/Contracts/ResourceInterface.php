<?php namespace Alba\Core\Contracts;

interface ResourceInterface {

	/**
	 * Display a listing of the resource.
	 *
	 * @param array $params to overload
	 */
	public function index($params = []);

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param array $attributes to fill on the object
	 * @return Model
	 */
	public function store($attributes);

	/**
	 * Display the specified resource.
	 *
	 * @param int $id of object
	 * @return Model
	 */
	public function show($id);

	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id of object to update
	 * @param array $attributes to fill on the object
	 * @return Model
	 */
	public function update($id, $attributes);

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id of object to remove
	 * @param bool $force delete
	 * @return bool
	 * 
	 */
	public function destroy($id, $force);
}