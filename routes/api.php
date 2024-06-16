<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// posts
Route::get('blog/posts', [\App\Http\Controllers\Api\Blog\PostController::class, 'index']);
Route::get('blog/posts/{id}', [\App\Http\Controllers\Api\Blog\PostController::class, 'show']);
Route::post('blog/posts', [\App\Http\Controllers\Api\Blog\PostController::class, 'create']);
Route::put('blog/posts/{id}', [\App\Http\Controllers\Api\Blog\PostController::class, 'update']);
Route::delete('blog/posts/{id}', [\App\Http\Controllers\Api\Blog\PostController::class, 'delete']);

//categories
Route::get('blog/categories', [\App\Http\Controllers\Api\Blog\CategoryController::class, 'index']);
Route::get('blog/categories/{id}', [\App\Http\Controllers\Api\Blog\CategoryController::class, 'show']);
Route::post('blog/categories', [\App\Http\Controllers\Api\Blog\CategoryController::class, 'create']);
Route::put('blog/categories/{id}', [\App\Http\Controllers\Api\Blog\CategoryController::class, 'update']);
