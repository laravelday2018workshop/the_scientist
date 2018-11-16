<?php

declare(strict_types=1);

namespace Acme\Common\ValueObject;

interface EntityID
{
    public function __toString(): string;
}
