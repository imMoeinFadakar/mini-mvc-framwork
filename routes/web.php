<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use Roocket\PocketCore\Router;
use Roocket\PocketCore\View;


Router::get('/articles/create' , [ArticleController::class , 'create']);

Router::post('/articles/create' , [ArticleController::class , 'store']);

Router::get('/articles' , [ArticleController::class , 'index']);

Router::get('/articles/update' , [ArticleController::class , 'articlesUpdate']);

Router::get('/articles/delete' , [ArticleController::class , 'deleteArticles']);

Router::get('/users' , [UserController::class , 'index']);

Router::view('/about' , 'about');

