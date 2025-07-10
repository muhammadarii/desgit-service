<?php

namespace App\Interface;

interface FamilyMemberRepositoryInterface
{
  public function getAll(
    ?string $search,
    ?int $limit,
    bool $execute
  );

  public function getAllPaginated(
    ?string $search,
    ?int $rowPerPage
  );
}