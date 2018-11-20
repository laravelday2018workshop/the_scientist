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
use App\Integration\Article\Mapper\Hydrator\HydrateArticle;
use App\Integration\Article\Mapper\Serializer\SerializeArticle;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use Psr\Log\LoggerInterface;
use stdClass;

final class ArticleQueryBuilderRepository implements ArticleRepository
{
    public const TABLE_NAME = 'articles';

    /**
     * @var DatabaseManager
     */
    private $database;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SerializeArticle
     */
    private $fromArticleMapper;

    /**
     * @var HydrateArticle
     */
    private $fromArrayMapper;

    public function __construct(
        DatabaseManager $database,
        SerializeArticle $fromArticleMapper,
        HydrateArticle $fromArrayMapper,
        LoggerInterface $logger
    ) {
        $this->database = $database;
        $this->logger = $logger;
        $this->fromArticleMapper = $fromArticleMapper;
        $this->fromArrayMapper = $fromArrayMapper;
    }

    public function getById(ArticleID $articleID): Article
    {
        try {
            $rawArticle =
                $this->database->table(self::TABLE_NAME)
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

        return ($this->fromArrayMapper)((array) $rawArticle);
    }

    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): ArticleCollection
    {
        if ($take > ArticleRepository::MAX_SIZE) {
            $take = ArticleRepository::MAX_SIZE;
        }

        try {
            $rawArticles = $this->database->table(self::TABLE_NAME)->select()->skip($skip)->take($take)->get();
        } catch (QueryException $e) {
            $this->logger->warning('database failure', ['exception' => $e]);
            throw new ImpossibleToRetrieveArticles($e);
        }

        $articles = \array_map(function (stdClass $rawArticle) {
            return ($this->fromArrayMapper)((array) $rawArticle);
        }, $rawArticles->toArray());

        return new ArticleCollection(...$articles);
    }

    /**
     * @throws ImpossibleToSaveArticle
     */
    public function add(Article $article): void
    {
        $rawArticle = ($this->fromArticleMapper)($article);

        try {
            $insert = $this->database->table(self::TABLE_NAME)->insert($rawArticle);
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'article' => $rawArticle]);
            throw new ImpossibleToSaveArticle($e);
        }

        if (false === $insert) {
            $this->logger->warning('impossible to add article', ['article' => $rawArticle]);
            throw new ImpossibleToSaveArticle();
        }
    }

    /**
     * @throws ImpossibleToSaveArticle
     */
    public function update(Article $article): void
    {
        $rawArticle = ($this->fromArticleMapper)($article);

        try {
            $update = $this->database->table(self::TABLE_NAME)->update($rawArticle);
        } catch (QueryException $e) {
            $this->logger->error('database failure', ['exception' => $e, 'article' => $rawArticle]);
            throw new ImpossibleToSaveArticle($e);
        }

        if (0 === $update) {
            $this->logger->warning('impossible to update article', ['article' => $rawArticle]);
            throw new ImpossibleToSaveArticle();
        }
    }
}
