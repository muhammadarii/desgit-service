<?php

namespace App\Repositories;

use App\Interface\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
  public function getAll(
    ?string $search,
    ?int $limit,
    bool $execute
  )
  {
    $query = User::where(function ($query) use ($search) {
      // jika ada parameter search dia akan melakukan  search, yang kita definisaikan pada model user
      if ($search) {
        $query->search($search);
      }
    });

    if ($limit) {
      // take berguna untuk mengambil beberapa berdasarkan limit
      $query->take($limit);
    }

    if ($execute) {
      return $query->get();
    }

    return $query;
  }
  
  public function getAllPaginated(
    ?string $search,
    ?int $rowPerPage
  ){
    $query = $this->getAll($search, $rowPerPage, false);
    return $query->paginate($rowPerPage);
  }

  public function create(
    array $data
    ){
    DB::beginTransaction();

    try {
      $user = new User;
      $user->name = $data['name'];
      $user->email = $data['email'];
      $user->password = bcrypt($data['password']);
      $user->save();

      DB::commit();
      return $user;
    }catch (\Exception $e) {
      DB::rollBack();

      throw new Exception($e->getMessage());
    }
  }
}