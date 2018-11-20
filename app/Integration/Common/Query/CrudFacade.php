<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Acme\Common\ValueObject\EntityID;

interface CrudFacade
{
    public function getById(EntityID $entityID): ?array;

    public function getAll(Pagination $pagination): array;

    public function save(array $data): void;

    public function update(EntityID $entityID, array $data): void;

    public function remove(EntityID $entityID): void;
}
