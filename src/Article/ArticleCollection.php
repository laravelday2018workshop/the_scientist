<?php

declare(strict_types=1);

namespace Acme\Article;

use ArrayObject;
use IteratorIterator;

final class ArticleCollection extends IteratorIterator
{
    /**
     * @var Article[]
     */
    private $articles;

    public function __construct(Article ...$articles)
    {
        $this->articles = $articles;
        parent::__construct(new ArrayObject($this->articles));
    }

    /**
     * @return Article[]
     */
    public function toArray(): array
    {
        return $this->articles;
    }
}
