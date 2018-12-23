<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryController extends ApiController
{
    public function __construct(){
        $this->middleware(['auth:api']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $categories = $user->categories;

        return $this->showAll($categories,200);
    }

    public function deleteIndex(Request $request){
        $user = $request->user();
        $categories = $user->DeleteCategories;

        return $this->showAll($categories,200);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->user_id = $user->id;

        $category->save();

        return $this->showOne($category,201);
        
    }
   
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'required|string'
        ]);

        $category = Category::findOrFail($id);
        if($category->user_id != $user->id) return $this->error(['message'=>'No permitido, esta categoria no se encuentra'],401);
        $category->name = $request->name;
        if($category->isDirty()) $category->save();

        return $this->showOne($category,200);
    }

    public function destroy(Request $request,$id)
    {
        $user = $request->user();
        $category = Category::findOrFail($id);
        if($category->user_id != $user->id) return $this->error(['message'=>'No permitido, esta categoria no se encuentra'],401);

        $category->delete();
        return $this->showOne($category,200);
    }

    public function restore(Request $request,$id)
    {
        $user = $request->user();
        $category = Category::withTrashed()->findOrFail($id);
        if($category->user_id != $user->id) return $this->error(['message'=>'No permitido, esta categoria no se encuentra'],401);

        $category->restore();
        return $this->showOne($category,200);
    }
}
