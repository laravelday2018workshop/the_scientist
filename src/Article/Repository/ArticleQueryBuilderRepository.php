<?php

declare(strict_types=1);

namespace Acme\Article\Repository;

use Acme\Article\Article;
use Acme\Article\ArticleCollection;
use Acme\Article\Mapper\ArticleMapper;
use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\ValueObject\ArticleID;
use Illuminate\Database\Query\Builder as QueryBuilder;

final class ArticleQueryBuilderRepository implements ArticleRepository
{
    private const TABLE_NAME = 'articles';

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var ArticleMapper
     */
    private $articleMapper;

    public function __construct(QueryBuilder $queryBuilder, ArticleMapper $articleMapper)
    {
        $this->queryBuilder = $queryBuilder;
        $this->articleMapper = $articleMapper;
    }

    public function getById(ArticleID $articleID): Article
    {
        $rawArticle = $this->queryBuilder
            ->select()
            ->from(self::TABLE_NAME)
            ->where('id', '=', (string) $articleID)
            ->first();

        if (null === $rawArticle) {
            throw new ArticleNotFound($articleID);
        }

        return $this->articleMapper->fromArray($rawArticle->toArray());
    }

    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): ArticleCollection
    {
        $rawArticles = $this->queryBuilder->select()->from(self::TABLE_NAME)->skip($skip)->take($take)->get();

        $articles = \array_map(function (array $rawArticle) {
            return $this->articleMapper->fromArray($rawArticle);
        }, $rawArticles->toArray());

        return new ArticleCollection($articles);
    }
}
