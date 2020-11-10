<?php

namespace App\Repository;

use App\Repository\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\{Collection,Model};

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection|Model[]
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param $id
     * @return Model
     */
    public function get($id): ?Model
    {
        return $this->model->find($id);
    }
}
