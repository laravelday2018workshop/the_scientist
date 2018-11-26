<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Academic\ValueObject\Email;
use Acme\Academic\ValueObject\Exception\InvalidEmail;
use Illuminate\Contracts\Validation\Rule;

class AcademicEmailRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            new Email($value);

            return true;
        } catch (InvalidEmail $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
