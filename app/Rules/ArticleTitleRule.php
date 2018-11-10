<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Article\ValueObject\Exception\InvalidTitle;
use Acme\Article\ValueObject\Title;
use Illuminate\Contracts\Validation\Rule;

class ArticleTitleRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            new Title($value);

            return true;
        } catch (InvalidTitle $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
