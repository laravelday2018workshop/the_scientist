<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use LaravelDay\Article\UseCase\ListArticles\ListArticles;

class ListArticlesController extends Controller
{
    public function __invoke()
    {
        $listArticles = new ListArticles();
        $articles = $listArticles();

        return response()->json($articles);
    }
}
