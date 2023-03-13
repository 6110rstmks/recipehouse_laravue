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

        if ($request->file_path)
        {
            $recipe->file_path = $request->file_path;
        }

        $recipe->save();

        $category->recipes()->syncWithoutDetaching($recipe->id);
        return 'complete';
    }

    public function delete(Recipe $recipe)
    {
        $recipe->delete();
        return 'complete';
    }

    public function imgUpload(Request $request)
    {
        Log::info($request);
        $file_name = $request->file->getClientOriginalName();

        $request->file->storeAs('public', $file_name);

        return 'ok';
    }
}
