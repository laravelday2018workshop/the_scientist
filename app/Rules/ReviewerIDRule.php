<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Common\ValueObject\Exception\InvalidID;
use Acme\Reviewer\ValueObject\ReviewerID;
use Illuminate\Contracts\Validation\Rule;

class ReviewerIDRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            ReviewerID::fromUUID($value);

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
