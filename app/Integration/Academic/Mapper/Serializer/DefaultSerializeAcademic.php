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

    public function __invoke(Academic $academic): array
    {
        return [
            'id' => (string) $academic->registrationNumber(),
            'articles' => \array_map($this->articleMapping, $academic->articles()->toArray()),
        ];
    }
}
