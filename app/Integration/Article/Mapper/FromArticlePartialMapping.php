<?php

declare(strict_types=1);

namespace App\Integration\Article\Mapper;

use Acme\Article\Article;

final class FromArticlePartialMapping
{
    public function __invoke(Article $article): array
    {
        return [
            'id' => (string) $article->id(),
            'title' => (string) $article->title(),
            'body' => (string) $article->body(),
            'academic_id' => (string) $article->academicID(),
            'reviewer_id' => (string) $article->reviewerID(),
            'published_at' => ($publishDate = $article->publishDate()) ? $publishDate->format('Y-m-d H:i:s') : null,
            'created_at' => $article->creationDate()->format('Y-m-d H:i:s'),
            'updated_at' => ($lastUpdateDate = $article->lastUpdateDate()) ? $lastUpdateDate->format('Y-m-d H:i:s') : null,
        ];
    }
}
