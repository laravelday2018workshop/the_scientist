<?php

declare(strict_types=1);

use App\Http\Controllers\CreateArticleController;
use App\Http\Controllers\GetArticleController;
use App\Http\Controllers\ListArticlesController;
use App\Http\Controllers\UpdateArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Api ready';
});

Route::get('/articles/', ListArticlesController::class);
Route::get('/articles/{id}', GetArticleController::class);
Route::post('/articles/', CreateArticleController::class);
Route::patch('/articles/{id}', UpdateArticleController::class);
