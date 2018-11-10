<?php

declare(strict_types=1);

namespace Acme\Article\UseCase\GetArticle;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\Repository\Exception\ImpossibleToRetrieveArticles;

final class GetArticleHandler
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @throws ArticleNotFound
     * @throws ImpossibleToRetrieveArticles
     */
    public function __invoke(GetArticleCommand $command): Article
    {
        return $this->articleRepository->getById($command->getArticleID());
    }
}
