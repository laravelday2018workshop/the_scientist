<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject;

use Acme\Academic\ValueObject\Exception\InvalidMajor;
use MyCLabs\Enum\Enum;
use UnexpectedValueException;

/**
 * @method static self BIOLOGICAL_SCIENCE()
 * @method static self COMPUTER_SCIENCE()
 */
final class Major extends Enum
{
    private const BIOLOGICAL_SCIENCE = 'biological_science';

    private const COMPUTER_SCIENCE = 'computer_science';

    public function __construct($value)
    {
        try {
            parent::__construct($value);
        } catch (UnexpectedValueException $e) {
            throw new InvalidMajor($value, $e);
        }
    }
}
