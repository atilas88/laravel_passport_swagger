<?php

namespace App\Repository;
use App\Models\User;
use Illuminate\Database\Query\Builder;

class UserRepository extends BaseRepository
{
  public function __construct(User $user)
  {
      parent::__construct($user);
  }
  public function ageAvg()
  {
      return $this->model->average('age');
  }
  public function countBySex()
  {
        $female = $this->model->where('sex','femenino')->count();
        $male = $this->model->where('sex','masculino')->count();
        return [
            'female' => $female,
            'male' => $male
        ];
  }
  public function maxAgeUser()
  {
      return $this->model->where('age',function (Builder $query) {
          $query->selectRaw('max(u.age)')->from('users as u');
      })->get();
  }
  public function minAgeUser()
  {
      return $this->model->where('age',function (Builder $query) {
          $query->selectRaw('min(u.age)')->from('users as u');
      })->get();
  }
}
