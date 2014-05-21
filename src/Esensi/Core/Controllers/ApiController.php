<?php namespace Esensi\Core\Controllers;

use \EsensiCoreRepositoryException as RepositoryException;
use \EsensiCoreRepository as Repository;
use \Esensi\Core\Contracts\ExceptionHandlerInterface;
use \Esensi\Core\Contracts\RepositoryInjectedInterface;
use \Esensi\Core\Contracts\PackagedInterface;
use \Esensi\Core\Traits\ApiExceptionHandlerTrait;
use \Esensi\Core\Traits\RepositoryInjectedTrait;
use \Esensi\Core\Traits\PackagedTrait;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Routing\Controller;

/**
 * Controller for accessing repositories as an API
 *
 * @author daniel <daniel@bexarcreative.com>
 */
class ApiController extends Controller implements ExceptionHandlerInterface,
    RepositoryInjectedInterface,
    PackagedInterface {

    /**
     * Make exceptions return a standard API exception format
     *
     * @see \Esensi\Core\Traits\ApiExceptionHandlerTrait
     */
    use ApiExceptionHandlerTrait;

    /**
     * Make use of Repository injection
     *
     * @see \Esensi\Core\Traits\RepositoryInjectedTrait
     */
    use RepositoryInjectedTrait;

    /**
     * Package this controller
     *
     * @see \Esensi\Core\Traits\PackagedTrait
     */
    use PackagedTrait;

    /**
     * Inject dependencies
     *
     * @param \Esensi\Core\Repositories\Repository $repository
     * @return \Esensi\Core\Controllers\ApiController
     */
    public function __construct(Repository $repository)
    {
        $this->setRepository($repository);
        $this->beforeFilter('@filterRequest');
    }

    /**
     * Binds error handlers for exceptions
     *
     * @param Route $route
     * @param Request $request
     * @return mixed
     */
    public function filterRequest($route, $request)
    {
        $class = $this;
        App::error(function(Exception $exception, $code, $fromConsole) use ($class)
        {
            Log::error($exception);
            return $class->handleException($exception);
        });

        App::error(function(RepositoryException $exception, $code, $fromConsole) use ($class)
        {
            return $class->handleException($exception);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Pagination\Paginator
     */
    public function index()
    {
        $filters = Input::only('max', 'order', 'sort', 'keywords');
        return $this->getRepository()
            ->setFilters($filters)
            ->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        return $this->getRepository()
            ->store(Input::all());
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id of resource
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->getRepository()
            ->show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of resource to update
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id)
    {
        return $this->getRepository()
            ->update($id, Input::all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @return boolean
     */
    public function destroy($id)
    {
        return $this->getRepository()
            ->destroy($id);
    }

}