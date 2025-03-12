<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\ArticleController;

Route::middleware(['api'])->group(function () {
    Route::get('/articles', [ArticleController::class, 'index']);
});