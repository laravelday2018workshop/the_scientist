<?php

declare(strict_types=1);

use App\Http\Controllers\GetAcademicController;
use App\Http\Controllers\GetArticleController;
use App\Http\Controllers\ListArticlesController;
use App\Http\Controllers\UpdateArticleController;
use App\Http\Controllers\WriteArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Api ready';
});

// Articles
Route::get('/articles/', ListArticlesController::class);
Route::get('/articles/{id}', GetArticleController::class);
Route::patch('/articles/{id}', UpdateArticleController::class);

// Academics
Route::post('/academics/{id}/articles', WriteArticleController::class);
Route::get('/academics/{id}', GetAcademicController::class);
