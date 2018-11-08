<?php

namespace Acme\Article\UseCase\GetArticle;

use Acme\Article\ValueObject\ArticleID;

final class GetArticleCommand
{
    /**
     * @var ArticleID
     */
    private $articleID;

    public function __construct(ArticleID $articleID)
    {
        $this->articleID = $articleID;
    }

    public function getArticleID(): ArticleID
    {
        return $this->articleID;
    }
}