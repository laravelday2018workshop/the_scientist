<?php

declare(strict_types=1);

namespace App\Integration\Article\Repository;

use Acme\Article\Article;
use Acme\Article\ArticleCollection;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\ValueObject\ArticleID;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class InMemoryArticleRepository implements ArticleRepository
{
    /** @var Collection<Article> */
    private $articles;

    public function __construct()
    {
        $this->articles = new Collection();
    }

    public function getById(ArticleID $articleID): Article
    {
        if (!$this->articles->has((string) $articleID)) {
            throw new ArticleNotFound($articleID);
        }

        return $this->articles->get((string) $articleID);
    }

    public function list(int $skip = self::DEFAULT_SKIP, int $take = self::DEFAULT_TAKE): ArticleCollection
    {
        return new ArticleCollection(...$this->articles->values());
    }

    public function nextID(): ArticleID
    {
        return ArticleID::fromUUID((string) Uuid::uuid4());
    }

    public function add(Article $article): void
    {
        $this->articles->put((string) $article->id(), $article);
    }
}
