<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Illuminate\Database\DatabaseManager;

final class CrudFacadeDefaultBuilder
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function build(string $table): CrudFacadeDefault
    {
        $builder = $this->databaseManager->table($table);

        return new CrudFacadeDefault(
            new QueryBuilderSelectById($builder),
            new QueryBuilderSelectAll($builder),
            new QueryBuilderInsert($builder),
            new QueryBuilderUpdate($builder),
            new QueryBuilderDelete($builder)
        );
    }
}
