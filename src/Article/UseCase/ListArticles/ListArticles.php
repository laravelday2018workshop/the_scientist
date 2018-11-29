<?php

declare(strict_types=1);

namespace LaravelDay\Article\UseCase\ListArticles;

class ListArticles
{
    public function __invoke(): array
    {
        return [[
            'body' => 'Questo è il body',
            'creationDate' => '2018-11-29 00:00:00',
            'lastUpdate' => '2018-11-29 00:00:00',
            'publishDate' => '2018-11-29 00:00:00',
        ]];
    }
}
