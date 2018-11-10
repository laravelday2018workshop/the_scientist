<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Article\ValueObject\ArticleID;
use Acme\Common\ValueObject\Exception\InvalidID;
use Illuminate\Contracts\Validation\Rule;

class ArticleIDRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            ArticleID::fromUUID($value);

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
