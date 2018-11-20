<?php

declare(strict_types=1);

namespace App\Integration\Common\Query;

use Acme\Common\ValueObject\EntityID;

interface SelectById
{
    public function __invoke(EntityID $entityID): array;
}
