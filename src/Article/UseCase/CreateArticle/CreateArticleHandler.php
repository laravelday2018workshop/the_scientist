<?php

declare(strict_types=1);

namespace Acme\Article\UseCase\CreateArticle;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;

final class CreateArticleHandler
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /** TODO: Use Event dispatcher in order to communicate that the article was created */
    public function __invoke(CreateArticleCommand $command): Article
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

        return $article;
    }
}
