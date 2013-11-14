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
	 */
	public function store();

	/**
	 * Display the specified resource.
	 *
	 */
	public function show($id);

	/**
	 * Update the specified resource in storage.
	 *
	 */
	public function update($id);

	/**
	 * Remove the specified resource from storage.
	 *
	 */
	public function destroy($id);
}