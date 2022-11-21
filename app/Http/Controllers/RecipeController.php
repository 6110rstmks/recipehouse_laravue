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

        // Log::debug(Recipe::latest()->get());
        // return Recipe::latest()->get();
        // Log::debug($category->recipes()->al l());
        // return $category->recipes()->find(1);
        return $category->recipes()->get();
    }

    public function store(Request $request, Category $category)
    {
        $recipe = new Recipe();

        $recipe->title = $request->title;

        $recipe->save();

        // $category->recipes()->syncWithoutDetaching($recipe->id);
        $category->recipes()->syncWithoutDetaching($recipe->id);

        return redirect()
            ->route('category.show', $category);
    }
}
