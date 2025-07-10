<?php

namespace App\Repositories;

use App\Interface\FamilyMemberRepositoryInterface;
use App\Models\FamilyMember;

class FamilyMemberRepository implements FamilyMemberRepositoryInterface
{
  public function getAll(
    ?string $search,
    ?int $limit,
    bool $execute
    ) {
      $query = FamilyMember::where(function ($query) use ($search) {
        if ($search) {
          $query->search($search);
        }
      });

      if ($limit) {
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
  ) {
      $query = $this ->getAll(
        $search,
        $rowPerPage,
        false
      );

      return $query->paginate($rowPerPage);
      }
}