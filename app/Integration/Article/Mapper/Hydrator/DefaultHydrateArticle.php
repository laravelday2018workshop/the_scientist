<?php

declare(strict_types=1);

namespace App\Integration\Article\Mapper\Hydrator;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\Article;
use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;
use Acme\Reviewer\ValueObject\ReviewerID;
use DateTimeImmutable;

final class DefaultHydrateArticle implements HydrateArticle
{
    public function __invoke(array $rawArticle): Article
    {
        return new Article(
            ArticleID::fromUUID($rawArticle['id']),
            new Title($rawArticle['title']),
            new Body($rawArticle['body']),
            AcademicRegistrationNumber::fromString($rawArticle['academic_id']),
            !empty($rawArticle['reviewer_id']) ? ReviewerID::fromUUID($rawArticle['reviewer_id']) : null,
            !empty($rawArticle['published_at']) ? new DateTimeImmutable($rawArticle['published_at']) : null,
            new DateTimeImmutable($rawArticle['created_at']),
            !empty($rawArticle['updated_at']) ? new DateTimeImmutable($rawArticle['updated_at']) : null
        );
    }
}
