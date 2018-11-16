<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject;

use MyCLabs\Enum\Enum;

/**
 * @method static self BIOLOGICAL_SCIENCE()
 * @method static self COMPUTER_SCIENCE()
 */
final class Major extends Enum
{
    private const BIOLOGICAL_SCIENCE = 'biological_science';

    private const COMPUTER_SCIENCE = 'computer_science';
}
