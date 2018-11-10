<?php

declare(strict_types=1);

namespace Acme\Article\UseCase\ListArticles;

use Acme\Article\ArticleCollection;
use Acme\Article\Repository\ArticleRepository;
use Acme\Article\Repository\Exception\ArticleNotFound;
use Acme\Article\Repository\Exception\ImpossibleToRetrieveArticles;

final class ListArticlesHandler
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
    public function __invoke(ListArticlesCommand $command): ArticleCollection
    {
        $skip = $command->getSkip();
        if (0 === $skip || null === $skip) {
            $skip = ArticleRepository::DEFAULT_SKIP;
        }

        $take = $command->getTake();
        if (0 === $take || null === $take) {
            $take = ArticleRepository::DEFAULT_TAKE;
        }

        return $this->articleRepository->list($skip, $take);
    }
}
