<?php

declare(strict_types=1);

namespace Acme\Article\UseCase\GetArticle;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;

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
     * @throws \Acme\Article\Repository\Exception\ArticleNotFound
     */
    public function __invoke(GetArticleCommand $command): Article
    {
        return $this->articleRepository->getById($command->getArticleID());
    }
}
