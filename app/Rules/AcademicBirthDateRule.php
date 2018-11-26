<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Academic\ValueObject\BirthDate;
use Acme\Academic\ValueObject\Exception\InvalidBirthDate;
use Illuminate\Contracts\Validation\Rule;

class AcademicBirthDateRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            BirthDate::fromString($value);

            return true;
        } catch (InvalidBirthDate $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
