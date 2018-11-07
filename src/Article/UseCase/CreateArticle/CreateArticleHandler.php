<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 11/7/18
 * Time: 7:04 PM.
 */

namespace Acme\Article\UseCase\CreateArticle;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;

final class CreateArticleHandler
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
//        $this->eventDispatcher   = $eventDispatcher;
    }

    public function __invoke(CreateArticleCommand $command): void
    {
        $article = Article::create(
            $this->articleRepository->nextID(),
            $command->getTitle(),
            $command->getBody(),
            $command->getAcademicID(),
            $command->getReviewerID(),
            new \DateTimeImmutable()
        );

        $this->articleRepository->add($article);

        //$this->eventDispatcher->dispatch(new ArticleCreated())
    }
}
