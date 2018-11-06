<?php

declare(strict_types=1);

namespace Acme\Article\Mapper;

use Acme\Article\Article;

interface ArticleMapper
{
    public function fromArray(array $rawArticle): Article;

    public function fromArticle(Article $article): array;
}
