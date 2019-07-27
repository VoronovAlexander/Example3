<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $model;

    /**
     * Метод поиска экземпляра модели по id
     *
     * @param int $id
     * @return Model
     */
    protected function getById(int $id): Model
    {
        $object = $this->model->find($id);
        return $object;
    }
}
