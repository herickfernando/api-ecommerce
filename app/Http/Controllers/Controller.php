<?php

namespace App\Http\Controllers;

use App\Domains\CRUDService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var CRUDService
     */
    protected $service;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $class = get_class($this);

        $service = str_replace('Controller', 'Service', $class);

        if (class_exists($service)) {
            $this->service = new $service();
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $paginate = $request->query('per_page', 10);

        $results = $this
            ->createQuery($request)
            ->list($paginate);

        if (!$request->has('per_page')) {
            $results = $this
                ->createQuery($request)
                ->listWithoutPaginate();
        }

        return response($results);
    }

    protected function getOrderBy(Request $request)
    {
        return $request
                ->get(CRUDService::ORDER_BY) ?? 'id';
    }

    protected function isDescending(Request $request)
    {
        return $request
            ->has(CRUDService::DESCENDING);
    }

    /**
     * @param Request $request
     * @return CRUDService
     */
    protected function createQuery(Request $request)
    {
        return $this
            ->service
            ->filter($request->get('search') ?? '')
            ->orderBy($this->getOrderBy($request), $this->isDescending($request));
    }
}
