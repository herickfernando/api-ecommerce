<?php

namespace App\Http\Controllers;

use App\CRUDService;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
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

    public function index(Request $request)
    {
        $paginate = $request->query('paginate');
        $search = $request->query('search') ?? '';
        $searchLike = sprintf('%%s%', $search);

        $columns = $this->columnsFilter();

        /** @var Builder $query */
        $query = $this
            ->service
            ->modelClass;

        foreach ($columns as $column) {
            $query
                ->where($column, 'like', $searchLike)
                ->orWhere(next($column), 'like', $searchLike);
        }

        if ($request->has('paginate')) {
            return $query->paginate($paginate);
        }

        return $query::all();
    }

    public function columnsFilter()
    {
        return $this->service->columnsFilter();
    }
}
