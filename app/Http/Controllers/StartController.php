<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use App\Follower;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class StartController extends ApiController
{
   public function __construct(){
        $this->middleware(['auth:api']);
    }

    public function index(Request $request){
    	$user = $request->user();

    	$ids = Follower::where('user_follower_id',$user->id)->where('acepted',1)->pluck('user_following_id');

    	$products = Product::whereIn('user_id',$ids)->with('user')->get();

    	

    	return $this->showAll($products,200);

    }
}
