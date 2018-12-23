<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class ProductController extends ApiController
{
    public function __construct(){
        $this->middleware(['auth:api']);
        //intebto de hacer in md el cuan cheque que el producto es del user(no hay exito)
        //$this->middleware(['isMine'])->only(['update','destroy']);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        //$products = $user->products->with('category');

        $products = Product::where('user_id',$user->id)->with('category')->get();

        //foreach ($products as $product) {
            //$product->category;
        //}


        return $this->showAll($products,200);
    }

    public function deleteIndex(Request $request){

        $user = $request->user();

        $products = $user->deleteProducts;

        return $this->showAll($products,200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|integer',
            'img' => 'image|max:2500',
            'description' => 'required|string',
            'price' => 'numeric',
            'address' => 'string',
            'country' => 'string',
            'state' => 'string',
            'colony' => 'string',
            'cp' => 'string',
            'bathrooms' => 'string',
            'ground' => 'string',
            'available' => 'integer',
        ]);

        $category_id = $request->category_id;

        $category = Category::findOrFail($category_id);

        $user = $request->user();

        if($category->user_id != $user->id) return $this->error(['message' =>'No autorizado'],401);

        

        $product = new Product($request->all());
        $product->user_id = $user->id;
        $product->img = 'product/sinfoto.png';

        if($request->img) {
            //Storage::disk('img')->delete($user->img_profile);
            $product->img = $request->img->store('product','img');
        }

        $product->save();

        return $this->showOne($product,201);
    }

    public function update(Request $request, $id)
    {
         $request->validate([
            'name' => 'string',
            'category_id' => 'integer',
            'img' => 'image|max:2500',
            'description' => 'string',
            'price' => 'numeric',
            'address' => 'string',
            'country' => 'string',
            'state' => 'string',
            'colony' => 'string',
            'cp' => 'string',
            'bathrooms' => 'string',
            'ground' => 'string',
            'available' => 'integer',
        ]);

        $user = $request->user();

        $product = Product::findOrFail($id);

        if($product->user_id != $user->id) return $this->error(['message' =>'No autorizado'],401);

        if($request->category_id){
            $category_id = $request->category_id;
            $category = Category::findOrFail($category_id);
            if($category->user_id != $user->id) return $this->error(['message' =>'No autorizado 1'],401);
        }

        $product->fill($request->all());

        if($request->img) {
            if($product->img != 'product/sinfoto.png') Storage::disk('img')->delete($product->img_profile);
            $product->img = $request->img->store('product','img');
        }
        if($product->isDirty()) $product->save();

        return $this->showOne($product,200);
    }

    public function destroy(Request $request,$id)
    {
        $user = $request->user();
        $product = Product::findOrFail($id);
        if($product->user_id != $user->id) return $this->error(['message' =>'No autorizado'],401);

        $product->delete();

        return $this->showOne($product,200);
    }

    public function restore(Request $request,$id){

        $user = $request->user();
        $product = Product::withTrashed()->findOrFail($id);
        if($product->user_id != $user->id) return $this->error(['message' =>'No autorizado'],401);

        $product->restore();

        return $this->showOne($product,200);
    }
}
