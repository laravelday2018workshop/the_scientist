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
use Illuminate\Support\Collection;

class InMemoryArticleRepository implements ArticleRepository
{
    const CONNECTION_LOST_MESSAGE = 'DB Connection lost';

    /** @var Collection<Article> */
    private $articles;

    /** @var bool */
    private $connectionLost = false;

    public function __construct()
    {
        $this->articles = new Collection();
    }

    /**
     * @throws ArticleNotFound
     * @throws \Exception
     */
    public function getById(ArticleID $articleID): Article
    {
        $this->checkConnection();

        if (!$this->articles->has((string) $articleID)) {
            throw new ArticleNotFound($articleID);
        }

        return $this->articles->get((string) $articleID);
    }

    /**
     * @throws ImpossibleToRetrieveArticles
     */
    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): ArticleCollection
    {
        // TODO: Implement list() method.
    }

    public function nextID(): ArticleID
    {
        // TODO: Implement nextID() method.
    }

    /**
     * @throws ImpossibleToSaveArticle
     */
    public function add(Article $article): void
    {
        $this->articles->put((string) $article->id(), $article);
    }

    public function looseConnection(): void
    {
        $this->connectionLost = true;
    }

    /**
     * @throws \Exception
     */
    private function checkConnection()
    {
        if ($this->connectionLost) {
            throw new \Exception(self::CONNECTION_LOST_MESSAGE);
        }
    }
}
