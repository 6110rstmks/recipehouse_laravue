<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class RecipeController extends Controller
{
    public function index(Category $category)
    {

        return $category->recipes()->get();
    }

    public function store(Request $request, Category $category)
    {
        $recipe = new Recipe();

        $recipe->title = $request->title;

        $recipe->save();

        $category->recipes()->syncWithoutDetaching($recipe->id);
        return $recipe;
    }
}
