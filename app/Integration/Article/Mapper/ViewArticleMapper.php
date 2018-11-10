<?php

declare(strict_types=1);

namespace App\Integration\Article\Mapper;

use Acme\Article\Article;

final class ViewArticleMapper implements ArticleMapper
{
    /**
     * @var FromArticlePartialMapping
     */
    private $fromArticlePartialMapping;

    public function __construct(FromArticlePartialMapping $fromArticlePartialMapping)
    {
        $this->fromArticlePartialMapping = $fromArticlePartialMapping;
    }

    public function fromArray(array $rawArticle): Article
    {
    }

    public function fromArticle(Article $article): array
    {
        return ($this->fromArticlePartialMapping)($article);
    }
}
