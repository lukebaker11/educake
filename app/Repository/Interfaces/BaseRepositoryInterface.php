<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface BaseRepositoryInterface
 * @package App\Repositories
 */
interface BaseRepositoryInterface
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $id
     * @return Model|null
     */
    public function get($id): ?Model;
}
