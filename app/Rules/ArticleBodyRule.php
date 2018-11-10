<?php

declare(strict_types=1);

namespace App\Rules;

use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Exception\InvalidBody;
use Illuminate\Contracts\Validation\Rule;

class ArticleBodyRule implements Rule
{
    /**
     * @var string
     */
    private $message;

    public function passes($attribute, $value): bool
    {
        try {
            new Body($value);

            return true;
        } catch (InvalidBody $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
