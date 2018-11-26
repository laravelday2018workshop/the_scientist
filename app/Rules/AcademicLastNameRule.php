<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Academic\ValueObject\Exception\InvalidLastName;
use Acme\Academic\ValueObject\LastName;
use Illuminate\Contracts\Validation\Rule;

class AcademicLastNameRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            new LastName($value);

            return true;
        } catch (InvalidLastName $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
