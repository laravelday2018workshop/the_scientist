<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Academic\ValueObject\AcademicID;
use Acme\Common\ValueObject\Exception\InvalidID;
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
            AcademicID::fromUUID($value);

            return true;
        } catch (InvalidID $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
