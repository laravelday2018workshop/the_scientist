<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Acme\Common\Query\Delete;
use Acme\Common\Query\Insert;
use Acme\Common\Query\Pagination;
use Acme\Common\Query\SelectAll;
use Acme\Common\Query\SelectById;
use Acme\Common\Query\Update;
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

    public function __construct(SelectById $selectById,
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

    public function getById(EntityId $entityId): ?array
    {
        return ($this->selectById)($entityId);
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
