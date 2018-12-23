<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



/*
 User
*/
Route::get('user/products','User\UserController@products');
Route::get('user/followers','User\UserController@followers');
Route::get('user/followings','User\UserController@followings');
Route::get('user/followers/count','User\UserController@countFollowers');
Route::get('user/followings/count','User\UserController@countFollowings');
Route::resource('user','User\UserController')->only(['index','store','destroy']);
Route::post('user/update','User\UserController@update');
Route::get('user/my-user','User\UserController@myUser');


//Route::post('user',['as'=> 'user.create','uses' => 'User\UserController@store']);
//Route::get('user/show',['as'=> 'user.show','uses' => 'User\UserController@show']);

/*
 folloer
*/
Route::post('follower/{id}','FollowerController@store');
Route::get('follower/list','FollowerController@list');
Route::post('follower/deny/{id}','FollowerController@deny');Route::post('follower/downFollower/{id}','FollowerController@downFollower');
Route::post('follower/acepted/{id}','FollowerController@acepted');

 /*
 categories
*/
Route::resource('category','CategoryController')->only(['index','store','update','destroy']);
Route::delete('category/restore/{id}','CategoryController@restore');
Route::get('category/showdelete','CategoryController@deleteIndex');

 /*
 /*products
 */

Route::resource('product','ProductController')->only(['index','store','update','destroy']);
Route::delete('product/restore/{id}','ProductController@restore');
Route::get('product/showdelete','ProductController@deleteIndex');

/*
ShowUser
*/
Route::get('view/user/{iduser}','ViewUserCOntroller@viewUser');
Route::get('view/user/{iduser}/categories','ViewUserCOntroller@userCategories');
Route::get('view/user/{iduser}/products','ViewUserCOntroller@userProducts');
Route::get('view/user/{iduser}/products/{idcategory}','ViewUserCOntroller@userCategoryProducts');
Route::post('view/usersearch','ViewUserCOntroller@searchUsers');

/*
ShowUser
*/
Route::get('start','StartController@index');

/*
 autentication
*/
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'ApiAuth\AuthController@login');
    //Route::post('signup', 'ApiAuth\AuthController@create');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::post('logout', 'ApiAuth\AuthController@logout');
        Route::get('user', 'ApiAuth\AuthController@user');
    });
});
