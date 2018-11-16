<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Academic\ValueObject\Excpetion\InvalidAcademicRegistrationNumber;
use Illuminate\Contracts\Validation\Rule;

class AcademicIDRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            AcademicRegistrationNumber::fromString($value);

            return true;
        } catch (InvalidAcademicRegistrationNumber $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
