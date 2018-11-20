<?php

declare(strict_types=1);

namespace App\Integration\Article\Mapper\Hydrator;

use Acme\Article\Article;

interface HydrateArticle
{
    public function __invoke(array $rawArticle): Article;
}
