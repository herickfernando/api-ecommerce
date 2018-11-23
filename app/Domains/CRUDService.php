<?php

namespace App\Domains;

use Illuminate\Database\Eloquent\Model;

abstract class CRUDService
{
    const ORDER_BY = 'orderBy';
    const DESCENDING = 'descending';

    protected $modelClass;
    protected $filter;
    private $orderBy;
    private $orderDesc = true;

    /**
     * @param array $data
     *
     * @return Model
     * @throws \Exception
     */
    public function create($data)
    {
        $model = new $this->modelClass();
        $this->fill($model, $data);
        $model->save();

        return $model;
    }

    /**
     * @param Model $model
     * @param array $data
     *
     * @return void
     * @throws \Exception
     */
    protected function fill(& $model, array $data)
    {
        $message = sprintf('Method fill is not implemented on %s', class_basename($this));
        throw new \Exception($message);
    }

    /**
     * @param string | Model $model
     * @param                $data
     *
     * @return Model
     * @throws \Exception
     */
    public function update($model, $data)
    {
        $model = $this->findByUUID($model);

        $this->fill($model, $data);
        $model->save();

        return $model;
    }

    /**
     * @param string | Model $model
     *
     * @return Model
     */
    protected function findByUUID($model)
    {
        if (is_string($model)) {
            $model = $this->modelClass::find($model);
        }

        return $model;
    }

    /**
     * @param Model | string $model
     * @throws \Exception
     */
    public function delete($model)
    {
        $model = $this->findByUUID($model);

        $model->delete();
    }

    /**
     * @param string $column
     * @param bool   $descending
     * @return CRUDService $this
     */
    public function orderBy($column, bool $descending)
    {
        $this->orderBy = $column;
        $this->orderDesc = $descending;

        return $this;
    }

    /**
     * @param string $searchTerm
     * @return CRUDService $this
     */
    public function filter(string $searchTerm)
    {
        $filterClass = $this->modelClass . "Filter";

        if (class_exists($filterClass)) {
            $filter = new $filterClass($searchTerm);
            $this->filter = $filter;
        }

        return $this;
    }

    public function listWithoutPaginate()
    {
        return $this
            ->createQuery()
            ->get();
    }

    protected function createQuery()
    {
        $query = $this->select();

        if ($this->filter) {
            $query = $query->filter($this->filter);
        }

        if (!empty($this->orderBy)) {
            $order = $this->orderDesc ? 'desc' : 'asc';

            $query = $query->orderBy($this->orderBy, $order);
        }

        return $query;
    }

    /**
     * @return mixed
     */
    protected function select()
    {
        return $this
            ->modelClass::select();
    }

    /**
     * @param int $paginate
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function list(int $paginate = 15)
    {
        return $this
            ->createQuery()
            ->paginate($paginate);
    }
}
