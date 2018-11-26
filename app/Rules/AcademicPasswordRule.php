<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Academic\ValueObject\Exception\InvalidPassword;
use Acme\Academic\ValueObject\Password;
use Illuminate\Contracts\Validation\Rule;

class AcademicPasswordRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            Password::fromClearPassword($value);

            return true;
        } catch (InvalidPassword $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
