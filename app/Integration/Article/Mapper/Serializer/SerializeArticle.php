<?php

declare(strict_types=1);

namespace App\Integration\Article\Mapper\Serializer;

use Acme\Article\Article;

interface SerializeArticle
{
    public function __invoke(Article $article): array;
}
