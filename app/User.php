<?php

namespace App;

use App\Category;
use App\Follower;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email', 
        'password',
        'img_profile',
        'img_page',
        'category',
        'description',
        'address',
        'phone',
        'whatsapp',
        'private'
    ];
    protected $dates =['deleted_at'];

    public function setNameAttribute($valor){
        $this->attributes['name'] = strtolower($valor);
    }

    public function getNameAttribute($valor){
        return ucfirst($valor);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'admin',
        'private'
    ];

    public function isPrivte(){
        return $this->private == 1;
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_follower_id', 'user_following_id')->where('acepted',1)->withTimestamps()->withPivot('id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_following_id', 'user_follower_id')->where('acepted',1)->withTimestamps()->withPivot('id');
    }

    public function aceptedFollowers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_following_id', 'user_follower_id')->where('acepted',0)->withTimestamps()->withPivot('id');
    }

    public function stuatusFollower($id){
        $data = [
            'info' => 0,
            'follower' => -1
        ];

        //si es perfil privado, buscamos una relacion de follower con el usuario

        $follower = Follower::where('user_follower_id',$id)->where('user_following_id',$this->id)->first();
        if($follower){
            //si esta acepatada continua la peticion
            if($follower->isAcepted()) {
                $data['info'] = 1;
                $data['follower'] = 1;
            }

            if(!$follower->isAcepted()) {
                $data['info'] = 0;
                $data['follower'] = 0;
            }
        }

        if(!$this->isPrivte()) $data['info'] = 1;

        //se rechasa el ver al usuario poque no hay ninguna peticion de follower
        return $data;
    }

    public function categories(){
        return $this->hasMany(Category::class)->orderBy('name');
    }
    public function deleteCategories(){
        return $this->hasMany(Category::class)->orderBy('name')->onlyTrashed();
    }

    public function products(){
        return $this->hasMany(Product::class)->orderBy('name');
    }
    public function deleteProducts(){
        return $this->hasMany(Product::class)->orderBy('name')->onlyTrashed();
    }

    public function productsCategory($id){
        return $this->hasMany(Product::class)->where('category_id',$id)->orderBy('name')->get();
    }

    public function userFollowers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_follower_id', 'user_following_id')->where('acepted',1)->withTimestamps()->withPivot('id')->pluck('name');
    }
}
