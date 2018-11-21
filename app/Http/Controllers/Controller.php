<?php

namespace App\Http\Controllers;

use App\CRUDService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
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
}
