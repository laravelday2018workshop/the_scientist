<?php
/**
 * Created by PhpStorm.
 * User: pdell
 * Date: 29/11/2018
 * Time: 15:03
 */

namespace LaravelDay\Article\UseCase\ListArticles;


class ListArticles
{

    public function __invoke(): array
    {
        return [
                [
                'title' => 'Articolo 1',
                'body' => 'Questo è un articolo',
                'creationDate' => '2018-11-29 00:00:00'
                ]
            ];
    }
}