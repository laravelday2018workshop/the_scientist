<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject;

use Acme\Academic\ValueObject\Exception\InvalidAcademicRegistrationNumber;
use Acme\Common\ValueObject\EntityID;

final class AcademicRegistrationNumber implements EntityID
{
    private const REGISTRATION_NUMBER_REGEX = '/^ACC-\d{10}-DZZ$/';
    private const REGISTRATION_NUMBER_FORMAT = 'ACC-{number}-DZZ';
    /**
     * @var string
     */
    private $registrationNumber;

    private function __construct(string $registrationNumber)
    {
        if (!\preg_match(self::REGISTRATION_NUMBER_REGEX, $registrationNumber)) {
            throw new InvalidAcademicRegistrationNumber($registrationNumber);
        }

        $this->registrationNumber = $registrationNumber;
    }

    public static function fromString(string $registrationNumber): self
    {
        return new self($registrationNumber);
    }

    public static function fromInteger(int $number): self
    {
        $registrationNumber = \str_replace('{number}', $number, self::REGISTRATION_NUMBER_FORMAT);

        return new self($registrationNumber);
    }

    public function isEquals(self $registrationNumber): bool
    {
        return $this->registrationNumber === $registrationNumber->registrationNumber;
    }

    private function __clone()
    {
    }

    public function __toString(): string
    {
        return $this->registrationNumber;
    }
}
