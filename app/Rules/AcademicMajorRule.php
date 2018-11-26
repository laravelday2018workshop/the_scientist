<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Academic\ValueObject\Exception\InvalidMajor;
use Acme\Academic\ValueObject\Major;
use Illuminate\Contracts\Validation\Rule;

class AcademicMajorRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            new Major($value);

            return true;
        } catch (InvalidMajor $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
