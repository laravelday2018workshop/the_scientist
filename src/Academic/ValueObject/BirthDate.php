<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject;

use Acme\Academic\ValueObject\Exception\InvalidBirthDate;
use DateTimeImmutable;

final class BirthDate
{
    public const MIN_AGE = 18;
    public const DATE_FORMAT = 'Y-m-d';

    /**
     * @var DateTimeImmutable
     */
    private $birthDate;

    private function __construct(DateTimeImmutable $birthDate)
    {
        $years = $birthDate->diff(new DateTimeImmutable('now'))->y;
        if ($years < self::MIN_AGE) {
            throw InvalidBirthDate::invalidAge($years);
        }

        $this->birthDate = $birthDate;
    }

    public static function fromString(string $birthDate): self
    {
        if (!$date = DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $birthDate)) {
            throw InvalidBirthDate::invalidValue($birthDate);
        }

        if ($date > new DateTimeImmutable()) {
            throw InvalidBirthDate::invalidValue($birthDate);
        }

        return new self($date);
    }

    public function __toString(): string
    {
        return $this->birthDate->format(self::DATE_FORMAT);
    }

    public function isEquals(self $birthDate): bool
    {
        return (string) $this === (string) $birthDate;
    }

    private function __clone()
    {
    }
}
