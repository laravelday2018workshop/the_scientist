<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Acme\Common\Query\Pagination;
use Acme\Common\ValueObject\EntityID;

interface CrudFacade
{
    public function getById(EntityId $entityId): ?array;

    public function getAll(Pagination $pagination): array;

    public function save(array $data): void;

    public function update(EntityID $entityID, array $data): void;

    public function remove(EntityID $entityID): void;
}
