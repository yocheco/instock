<?php

namespace App\Http\Controllers\ApiAuth;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class AuthController extends ApiController
{
    
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] img_profile (null)
     * @param  [string] img_page (null)
     * @param  [string] category (null)
     * @param  [string] description (null)
     * @param  [string] address (null)
     * @param  [string] phone (null)
     * @param  [string] whatsapp (null)
     * @return [string] message (null)
     */
    public function create (Request $request){
    	$request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $request->admin = 0;
        $request->password = bcrypt($request->password);
        $user = User::create($request->all);

         $user->save();

         return $this->showOne($user,201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))

            return $this->error([
                'message' => 'No te encontramos en el sistema'
            ],401);
            
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return $this->ok([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ],200);
    }
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        //$token->revoke();
        $token->delete();

        return $this->ok(['message' =>'Saliste del sistema'],200);
    }
}
