<?php

declare(strict_types=1);

namespace App\Integration\Article\Repository;

use Acme\Article\Article;
use Acme\Article\ArticleCollection;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\Repository\Exception\ImpossibleToRetrieveArticles;
use Acme\Article\Repository\Exception\ImpossibleToSaveArticle;
use Acme\Article\ValueObject\ArticleID;
use App\Integration\Article\Mapper\ArticleMapper;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use stdClass;

final class ArticleQueryBuilderRepository implements ArticleRepository
{
    private const TABLE_NAME = 'articles';

    /**
     * @var QueryBuilder
     */
    private $database;

    /**
     * @var ArticleMapper
     */
    private $articleMapper;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(DB $database, ArticleMapper $articleMapper, LoggerInterface $logger)
    {
        $this->database = $database;
        $this->articleMapper = $articleMapper;
        $this->logger = $logger;
    }

    public function getById(ArticleID $articleID): Article
    {
        try {
            $rawArticle =
                DB::table(self::TABLE_NAME)
                    ->select()
                    ->where('id', '=', (string) $articleID)
                    ->first();
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'article_id' => (string) $articleID]);
            throw new ImpossibleToRetrieveArticles($e);
        }

        if (null === $rawArticle) {
            $this->logger->warning('article not found', ['article_id' => (string) $articleID]);
            throw new ArticleNotFound($articleID);
        }

        return $this->articleMapper->fromArray((array) $rawArticle);
    }

    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): ArticleCollection
    {
        if ($take > ArticleRepository::MAX_SIZE) {
            $take = ArticleRepository::MAX_SIZE;
        }

        try {
            $rawArticles = DB::table(self::TABLE_NAME)->select()->skip($skip)->take($take)->get();
        } catch (QueryException $e) {
            $this->logger->warning('database failure', ['exception' => $e]);
            throw new ImpossibleToRetrieveArticles($e);
        }

        $articles = \array_map(function (stdClass $rawArticle) {
            return $this->articleMapper->fromArray((array) $rawArticle);
        }, $rawArticles->toArray());

        return new ArticleCollection(...$articles);
    }

    public function nextID(): ArticleID
    {
        return ArticleID::fromUUID((string) Uuid::uuid4());
    }

    /**
     * @throws ImpossibleToSaveArticle
     */
    public function add(Article $article): void
    {
        $rawArticle = $this->articleMapper->fromArticle($article);

        try {
            $insert = DB::table(self::TABLE_NAME)->insert($rawArticle);
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'article' => $rawArticle]);
            throw new ImpossibleToSaveArticle($e);
        }

        if (false === $insert) {
            $this->logger->warning('impossible to add article', ['article' => $rawArticle]);
            throw new ImpossibleToSaveArticle();
        }
    }
}
