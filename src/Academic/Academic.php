<?php

declare(strict_types=1);

namespace Acme\Academic;

use Acme\Academic\ValueObject\AcademicRegistrationNumber;
use Acme\Article\Article;
use Acme\Article\ArticleCollection;

final class Academic
{
    /**
     * @var AcademicRegistrationNumber
     */
    private $registrationNumber;
    /**
     * @var ArticleCollection
     */
    private $articles;

    public function __construct(
        AcademicRegistrationNumber $registrationNumber,
        ArticleCollection $articles
    ) {
        $this->registrationNumber = $registrationNumber;
        $this->articles = $articles;
    }

    public function registrationNumber(): AcademicRegistrationNumber
    {
        return $this->registrationNumber;
    }

    public function articles(): ArticleCollection
    {
        return $this->articles;
    }

    public function write(Article $article): void
    {
        $this->articles = $this->articles->withArticle($article);
    }
}
