<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;


class UserController extends ApiController
{
    public function __construct(){
        $this->middleware(['auth:api'])->except(['store']);
        //$this->middleware(['viewUser'])->only(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->error('hola',201);
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function store (Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'admin' => 0,
            'private' => 0
        ]);

         $user->save();

         return $this->showOne($user,201);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [img] img_profile
     * @param  [img] img_page
     * @param  [string] category
     * @param  [string] description
     * @param  [string] address
     * @param  [string] phone
     * @param  [string] whatsapp
     * @param  [string] private
     * @return [model] User
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'string',
            'email' => 'string|email|unique:users,'.$user->id,
            'img_profile' => 'image|mimes:jpg,jpeg|max:2500',
            'img_page' => 'image|mimes:jpg,jpeg|max:2500',
            'category' => 'string',
            'description' => 'string',
            'address' => 'string',
            'phone' => 'string',
            'whatsapp' => 'string',
            'private' => 'integer',
        ]);

        if($request->name) $user->name = $request->name;
        if($request->email) $user->email = $request->email;
        if($request->category) $user->category = $request->category;
        if($request->description) $user->description = $request->description;
        if($request->address) $user->address = $request->address;
        if($request->phone) $user->phone = $request->phone;
        if($request->whatsapp) $user->whatsapp = $request->whatsapp;
        if($request->private) $user->private = $request->private;

        if($request->img_profile) {
            Storage::disk('img')->delete($user->img_profile);
            $user->img_profile = $request->img_profile->store('profile','img');
        } 
        if($request->img_page){
            Storage::disk('img')->delete($user->img_page);
            $user->img_page = $request->img_page->store('page','img');
        } 

        if($user->isDirty()) $user->save();

        return $this->showOne($user,200);

    }

    public function followers(Request $request){
         $user = $request->user();
         return $this->showAll($user->followers,200,true,10);
    }

     public function followings(Request $request){
         $user = $request->user();
         return $this->showAll($user->followings,200,true,10);
    }

    public function countFollowers(Request $request){
         $user = $request->user();
         return $this->ok(['followers' => $user->followers->count()],200);
    }

     public function countFollowings(Request $request){
         $user = $request->user();
         return $this->ok(['followings' => $user->followings->count()],200);
    }

    public function myUser(Request $request)
    {
        $user = $request->user();
        return $this->showOne($user,200);
    }
    public function destroy($id)
    {
        //
    }
}
