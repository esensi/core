<?php namespace Alba\Core;

use Controller;
use Input;
use Alba\Core\Contracts\ResourceInterface;

class CoreResource extends Controller implements ResourceInterface{

	/**
	 * The max number of results that paginator should return
	 *
	 * @var integer
	 */
	public $max = 25;

	/**
	 * The default field to order by
	 *
	 * @var string
	 */
	public $order = 'id';

	/**
	 * The default sort direction
	 *
	 * @var string
	 */
	public $sort = 'desc';

	/**
	 * The keyword for full-text search
	 *
	 * @var string
	 */
	public $keyword;

	/**
	 * The start date for searching after
	 *
	 * @var string
	 */
	public $start;

	/**
	 * The end date for searching before
	 *
	 * @var string
	 */
	public $end;

	/**
	 * The where clauses
	 *
	 * @var Closure
	 */
	public $where;

	/**
	 * The API returns a collection but the original Paginator can be accessed here
	 *
	 * @var Paginator
	 */
	public $paginator;

	/**
	 * The array to store column information
	 *
	 * @var array
	 */
	public $columns = [];

	/**
	 * The overload defaults
	 *
	 * @var string
	 */
	public $defaults = [
		'order'		=> 'id',
		'sort'		=> 'desc',
		'max'		=> 25,
		'keyword'	=> '',
		'start'		=> '',
		'end'		=> '',
		];

	/**
	 * Display a listing of the resource.
	 *
	 * @param array $params to overload
	 */
	public function index($params = []){

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 */
	public function store(){

	}

	/**
	 * Display the specified resource.
	 *
	 */
	public function show($id){

	}

	/**
	 * Update the specified resource in storage.
	 *
	 */
	public function update($id){

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 */
	public function destroy($id){

	}

	/**
	 * Overload attributes with parameters, and GET inputs
	 *
	 * @param array $params
	 * @return void
	 */
	protected function overload($params = [])
	{
		// Overload defaults with inputs
		foreach($this->defaults as $key => $default)
		{
			// Overload with input
			$this->$key = Input::get($key, $default);

			// Overload defaults with params
			if(isset($params[$key]))
			{
				$this->$key = $params[$key];
			}

			// Convert strings to arrays when needed
			if(is_array($default) && !is_array($this->$key))
			{
				$this->$key = explode(',', $this->$key);
			}
		}

		// Setup default where
		$this->where = (isset($params['where'])) ? $params['where'] : function(){ };

		// Do some cleanup
		$this->sort = strtolower($this->sort);
	}

	/**
	 * Setup keyword full-text search
	 *
	 * @param array $fields to search
	 */
	protected function keyword($fields = [])
	{
		// Setup keyword search
		$keyword = $this->keyword;
		if ( $keyword != $this->defaults['keyword'] ) {
			$this->where = function( $query ) use ($keyword, $fields)
				{
					foreach($fields as $field)
					{
						$query->orWhere($field, 'LIKE', '%' . $keyword . '%');
					}
				};
		}
	}

	/**
	 * Make columns
	 *
	 * @param array $fields to make columns for
	 */
	public function makeColumns($fields = [])
	{
		foreach($fields as $field => $name)
		{
			// Copy paginator
			$paginator = clone $this->paginator;
			
			// Get the sort direction for column
			$sort = ($this->sort == 'asc') ? 'asc' : 'desc';
			$rsort = ($this->sort == 'asc') ? 'desc' : 'asc';
			$sort = ($this->order==$field) ? $rsort : $sort;

			// Generate the URL
			$url = ($name) ? $paginator->appends([
				'order'	=> $field,
				'sort'	=> $sort
				])->getUrl(1) : '';

			// Determine active class
			$class = $this->order == $field ? 'active' : '';

			// Add the column to the columns
			$this->columns[$field] = [
					'id' => $field,
					'class' => $class,
					'sort' => $sort,
					'name' => ($name) ? $name : $field,
					'url' => $url,
				];
		}

		return $this->columns;
	}
}