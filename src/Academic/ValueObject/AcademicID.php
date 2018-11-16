<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject;

use Acme\Common\ValueObject\EntityID;
use Acme\Common\ValueObject\UUIDTrait;

final class AcademicID implements EntityID
{
    use UUIDTrait;
}
