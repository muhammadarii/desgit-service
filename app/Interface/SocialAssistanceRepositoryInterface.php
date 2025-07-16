<?php

namespace App\Interface;

interface SocialAssistanceRepositoryInterface
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

  public function create(
    array $data
  );

  public function getById(
    string $id
  );

  public function update(
    string $id,
    array $data
  );

  public function destroy(
    string $id
  );
}