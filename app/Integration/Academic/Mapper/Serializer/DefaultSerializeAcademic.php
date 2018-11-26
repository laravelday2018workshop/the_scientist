<?php

declare(strict_types=1);

namespace App\Integration\Academic\Mapper\Serializer;

use Acme\Academic\Academic;
use App\Integration\Article\Mapper\Serializer\SerializeArticle;

final class DefaultSerializeAcademic implements SerializeAcademic
{
    /**
     * @var SerializeArticle
     */
    private $articleMapping;

    public function __construct(SerializeArticle $articleMapping)
    {
        $this->articleMapping = $articleMapping;
    }

    public function withPassword(Academic $academic): array
    {
        return [
            'id' => (string) $academic->registrationNumber(),
            'first_name' => (string) $academic->firstName(),
            'last_name' => (string) $academic->lastName(),
            'email' => (string) $academic->email(),
            'password' => (string) $academic->password(),
            'major' => (string) $academic->major(),
            'birth_date' => (string) $academic->birthDate(),
            'articles' => \array_map($this->articleMapping, $academic->articles()->toArray()),
        ];
    }

    public function withoutPassword(Academic $academic): array
    {
        $map = $this->withPassword($academic);
        unset($map['password']);

        return $map;
    }
}
