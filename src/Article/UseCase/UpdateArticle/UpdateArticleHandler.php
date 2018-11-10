<?php

declare(strict_types=1);

namespace Acme\Article\UseCase\UpdateArticle;

use Acme\Article\Article;
use Acme\Article\Repository\ArticleRepository;

final class UpdateArticleHandler
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /** TODO: Use Event dispatcher in order to communicate that the article was updated */
    public function __invoke(UpdateArticleCommand $command): Article
    {
        $article = $this->articleRepository->getById($command->getArticleID());
        $article->updateWithReviewChanges($command->getTitle(), $command->getBody());

        $this->articleRepository->update($article);

        return $article;
    }
}
