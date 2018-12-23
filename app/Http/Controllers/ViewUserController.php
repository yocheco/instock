<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ViewUserController extends ApiController
{
	public function __construct(){
        $this->middleware(['auth:api'])->except(['store']);
        //$this->middleware(['viewUser'])->only(['show']);
    }

    public function searchUsers(Request $request){
        $search = $request->s;
        $users = User::where('name','like','%'.$search.'%')->orWhere('category','like','%'.$search.'%')->get();

        return $this->showAll($users,200,true,10);
    }

    public function viewUser(Request $request,$iduser){

    	$y_user = $request->user();
        $categories = null;
        $message = null;
        if($iduser == $y_user->id){
            return $this->ok(
            [
                'user'=>$y_user,
                'categories'=>$y_user->categories,
                'follower'=> -2,
                'status'=> 'soy yo'
            ],200);
        }
        $user = User::findOrFail($iduser);
        $data = $user->stuatusFollower($y_user->id);

        if($data['info'] == 0){
            $message = 'Cuenta privada';
            $categories = 'Cuenta privada';
        }else{
            $message = 'Envio de informacion del usuario';
            $categories = $user->categories;
        }

        return $this->ok(
            [
                'user'=>$user,
                'categories'=>$categories,
                'follower'=>$data['follower'],
                'status'=> $message
            ],200);
    }

    public function userCategories(Request $request,$iduser){
    	$y_user = $request->user();
        $categories = null;
        $message = null;
        if($iduser == $y_user->id){
            return $this->showAll($y_user->categories,200,false);
        }
        $user = User::findOrFail($iduser);
        $data = $user->stuatusFollower($y_user->id);

        if($data['info'] == 0){
            return $this->error(['message'=>'Cuenta privada'],200);
        }else{
            return $this->showAll($user->categories,200,false);
        }
    }

    public function userProducts(Request $request,$iduser){
    	$y_user = $request->user();

        if($iduser == $y_user->id){
        	$products = Product::where('user_id',$y_user->id)->with('category')->get();
        	
            return $this->showAll($products,200);
        }
        $user = User::findOrFail($iduser);
        $data = $user->stuatusFollower($y_user->id);

        if($data['info'] == 0){
            return $this->error(['message'=>'Cuenta privada'],401);
        }else{
        	$products = Product::where('user_id',$user->id)->with('category')->get();
        	
            return $this->showAll($products,200);
        }
    }

    public function userCategoryProducts(Request $request,$iduser,$idcategory){

    	$y_user = $request->user();

        if($iduser == $y_user->id){
        	$products = Product::where('user_id',$y_user->id)->where('category_id',$idcategory)->with('category')->get();
            return $this->showAll($products,200);
        }
        $user = User::findOrFail($iduser);
        $data = $user->stuatusFollower($y_user->id);

        if($data['info'] == 0){
            return $this->error(['message'=>'Cuenta privada'],401);
        }else{
        	$products = $user->productsCategory($idcategory);
        	
            return $this->showAll($products,200);
        }
    }
}
