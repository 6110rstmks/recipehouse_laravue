<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::latest()->get();
    }

    public function show(Category $category)
    {
        Log::debug($category);
        return $category;
    }

    public function maxCategory()
    {
        $maxId = Category::max('id');

        Log::debug($maxId);

        // if category is not exist...
        if ($maxId == null)
        {
            return;
        }

        return Category::find(Category::max('id'));
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return $category;
    }

    public function store(Request $request)
    {
        // Log::debug($request);
        $category = new Category();
        $category->title = $request->title;
        $category->save();

        return 'kanryou';

        // return redirect()
        // ->route('category.index');
    }
}
