<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RecipeController;




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories', [CategoryController::class, 'index']);
// Route::get('/', [CategoryController::class, 'index']);


// Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/categories/{category}', [CategoryController::class, 'show'])
    ->name('category.show');

Route::post('/categories/store', [CategoryController::class, 'store']);


Route::get('/max', [CategoryController::class, 'maxCategory']);
// なぜか '/categories/max'のurlだとうまくいかない。
// Route::get('/categories/max', [CategoryController::class, 'maxCategory']);

Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);



/* --------------------------------------------------------- */
                /* RecipeController */

Route::get('/categories/{category}/recipes', [RecipeController::class, 'index']);

Route::post('/categories/{category}/recipes/store', [RecipeController::class, 'store']);

