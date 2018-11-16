<?php

declare(strict_types=1);

namespace Acme\Common\Query;

use Acme\Common\ValueObject\EntityID;

interface Delete
{
    public function __invoke(EntityID $entityID): void;
}
