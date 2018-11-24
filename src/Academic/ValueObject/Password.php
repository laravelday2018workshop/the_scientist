<?php

declare(strict_types=1);

namespace Acme\Academic\ValueObject;

use Acme\Academic\ValueObject\Exception\InvalidPassword;

final class Password
{
    public const MIN_LENGTH = 6;

    private const UNKNOWN_ALGORITHM = 0;

    /**
     * @var string
     */
    private $password;

    private function __construct(string $password)
    {
        if (self::UNKNOWN_ALGORITHM === \password_get_info($password)['algo']) {
            throw InvalidPassword::fromError();
        }

        $this->password = $password;
    }

    public static function fromClearPassword(string $password): self
    {
        if (\mb_strlen($password) < self::MIN_LENGTH) {
            throw InvalidPassword::fromLength();
        }

        return new self(\password_hash($password, PASSWORD_DEFAULT));
    }

    public static function fromHashedPassword(string $password): self
    {
        return new self($password);
    }

    public function isEqual(string $password): bool
    {
        return \password_verify($password, $this->password);
    }

    private function __clone()
    {
    }

    public function __toString(): string
    {
        return $this->password;
    }
}
