<?php

declare(strict_types=1);

namespace App\Integration\Article\Mapper;

use Acme\Article\Article;
use const DATE_ATOM;

final class ViewArticleMapper implements ArticleMapper
{
    public function fromArray(array $rawArticle): Article
    {
    }

    public function fromArticle(Article $article): array
    {
        return [
            'id' => (string) $article->id(),
            'title' => (string) $article->title(),
            'body' => (string) $article->body(),
            'academic_id' => (string) $article->academicID(),
            'reviewer_id' => (string) $article->reviewerID(),
            'published_at' => ($publishDate = $article->publishDate()) ? $publishDate->format(DATE_ATOM) : null,
            'created_at' => ($creationDate = $article->creationDate()) ? $creationDate->format(DATE_ATOM) : null,
            'updated_at' => ($lastUpdateDate = $article->lastUpdateDate()) ? $lastUpdateDate->format(DATE_ATOM) : null,
        ];
    }
}
