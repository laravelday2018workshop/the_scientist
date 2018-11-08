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
    /** @var Collection<Article> */
    private $articles;

    public function __construct()
    {
        $this->articles = new Collection();
    }

    /**
     * @throws ArticleNotFound
     */
    public function getById(ArticleID $articleID): Article
    {
//        $articles = $this->articles->filter(function (Article $article) use ($articleID) {
//            return $articleID->isEquals($article->id());
//        });
//
//        if ($articles->count() !== 1) {
//            throw new ArticleNotFound($articleID);
//        }

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
}
