<?php

declare(strict_types=1);

namespace Acme\Article\UseCase\ListArticles;

final class ListArticlesCommand
{
    /**
     * @var int|null
     */
    private $skip;

    /**
     * @var int|null
     */
    private $take;

    public function __construct(?int $skip, ?int $take)
    {
        $this->skip = $skip;
        $this->take = $take;
    }

    public function getSkip(): ?int
    {
        return $this->skip;
    }

    public function getTake(): ?int
    {
        return $this->take;
    }
}
