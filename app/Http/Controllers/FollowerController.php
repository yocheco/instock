<?php

namespace App\Http\Controllers;

use App\User;
use App\Follower;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class FollowerController extends ApiController
{
	public function __construct(){
        $this->middleware(['auth:api']);
    }

    /**
     * Create follower
     *
     * @param  [id] user_following_id
     * @return [string] message
     */
    public function store(Request $request,$id){

    	$user = $request->user();
    	$userFollowing = User::findOrFail($id);

    	$odlfollower = Follower::where('user_follower_id',$user->id)->where('user_following_id',$userFollowing->id)->first();

    	if($odlfollower) return $this->ok(['message' => 'Solo puedes enviar una peticion de seguimiento'],200);
    	if($user->id == $id) return $this->ok(['message' => 'No te puedes Seguir'],200);

    	$follower = new Follower();

    	$follower->user_follower_id = $user->id;
    	$follower->user_following_id = $userFollowing->id;

    	if($userFollowing->isPrivte()) $follower->acepted = 0;

    	$follower->save();

    	return $this->ok(['message' => 'Peticion de seguimiento enviada'],200);
    }

    public function list(Request $request){
    	$user = $request->user();
    	return $this->showAll($user->aceptedFollowers,200,true,10);
    }

    public function acepted(Request $request,$id){
    	$user = $request->user();
    	$follower = Follower::findOrFail($id);

    	if($follower->user_following_id != $user->id) return $this->error(['message'=>'Esta solicitud no es para ti'],401);

    	$follower->acepted = 1;
    	$follower->save();

    	return $this->showOne($follower,200);
    }

    public function deny(Request $request,$id){
    	$user = $request->user();
    	$follower = Follower::findOrFail($id);

    	if($follower->user_following_id != $user->id) return $this->error(['message'=>'Esta solicitud no es para ti'],401);

    	$follower->forceDelete();

    	return $this->showOne($follower,200);
    }

    public function downFollower(Request $request,$id){
    	$user = $request->user();
    	$userFollowing = User::findOrFail($id);
    	$follower = Follower::where('user_follower_id',$user->id)->where('user_following_id',$id)->first();
    	if(!$follower) return $this->error(['message'=>'No sigues a ete usuario'],401);

    	$follower->forceDelete();
    	//dd($follower);
    	return $this->showOne($follower,200);
    }
}
