<?php

declare(strict_types=1);

namespace Acme\Article\Repository;

use Acme\Article\Article;
use Acme\Article\ArticleCollection;
use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\Repository\Exception\ImpossibleToRetrieveArticles;
use Acme\Article\Repository\Exception\ImpossibleToSaveArticle;
use Acme\Article\ValueObject\ArticleID;

interface ArticleRepository
{
    public const DEFAULT_SKIP = 0;

    public const DEFAULT_TAKE = 10;

    public const MAX_SIZE = 20;

    /**
     * @throws ArticleNotFound
     * @throws ImpossibleToRetrieveArticles
     */
    public function getById(ArticleID $articleID): Article;

    /**
     * @throws ImpossibleToRetrieveArticles
     */
    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): ArticleCollection;

    public function nextID(): ArticleID;

    /**
     * @throws ImpossibleToSaveArticle
     */
    public function add(Article $article): void;

    /**
     * @throws ImpossibleToSaveArticle
     */
    public function update(Article $article): void;
}
