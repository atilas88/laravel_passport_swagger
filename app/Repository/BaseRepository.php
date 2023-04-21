<?php

namespace App\Repository;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $model;
    private $relations;
   public function __construct(Model $model,$relations = [])
   {
        $this->model = $model;
        $this->relations = $relations;
   }
    public function all()
    {
        $query = $this->model;

        if(!empty($this->relations)) {
            $query = $query->with($this->relations);
        }

        return $query->get();
    }

    public function get(int $id)
    {
        return $this->model->find($id);
    }

    public function save(Model $model)
    {
        $model->save();

        return $model;
    }

    public function delete(Model $model)
    {
        $model->delete();
    }
}
