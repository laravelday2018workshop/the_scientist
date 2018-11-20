<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Acme\Common\ValueObject\EntityID;

final class CrudFacadeDefault implements CrudFacade
{
    /**
     * @var SelectById
     */
    private $selectById;

    /**
     * @var SelectAll
     */
    private $selectAll;

    /**
     * @var Insert
     */
    private $insert;

    /**
     * @var Update
     */
    private $update;

    /**
     * @var Delete
     */
    private $delete;

    public function __construct(
        SelectById $selectById,
        SelectAll $selectAll,
        Insert $insert,
        Update $update,
        Delete $delete
    ) {
        $this->selectById = $selectById;
        $this->selectAll = $selectAll;
        $this->insert = $insert;
        $this->update = $update;
        $this->delete = $delete;
    }

    public function getById(EntityID $entityID): ?array
    {
        return ($this->selectById)($entityID);
    }

    public function getAll(Pagination $pagination): array
    {
        return ($this->selectAll)($pagination);
    }

    public function save(array $data): void
    {
        ($this->insert)($data);
    }

    public function update(EntityID $entityID, array $data): void
    {
        ($this->update)($entityID, $data);
    }

    public function remove(EntityID $entityID): void
    {
        ($this->delete)($entityID);
    }
}
