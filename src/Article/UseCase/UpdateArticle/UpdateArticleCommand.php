<?php

declare(strict_types=1);

namespace Acme\Article\UseCase\UpdateArticle;

use Acme\Article\ValueObject\ArticleID;
use Acme\Article\ValueObject\Body;
use Acme\Article\ValueObject\Title;

final class UpdateArticleCommand
{
    /**
     * @var ArticleID
     */
    private $articleID;

    /**
     * @var Title
     */
    private $title;

    /**
     * @var Body
     */
    private $body;

    public function __construct(ArticleID $articleID, Title $title, Body $body)
    {
        $this->articleID = $articleID;
        $this->title = $title;
        $this->body = $body;
    }

    public function getArticleID(): ArticleID
    {
        return $this->articleID;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getBody(): Body
    {
        return $this->body;
    }
}
