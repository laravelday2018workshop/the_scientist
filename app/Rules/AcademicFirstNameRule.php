<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Academic\ValueObject\Exception\InvalidFirstName;
use Acme\Academic\ValueObject\FirstName;
use Illuminate\Contracts\Validation\Rule;

class AcademicFirstNameRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            new FirstName($value);

            return true;
        } catch (InvalidFirstName $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
