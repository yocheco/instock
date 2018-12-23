<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Follower extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_follower_id',
        'user_following_id', 
        'acepted'
    ];
    protected $dates =['deleted_at'];

    Public function isAcepted(){
    	return $this->acepted == 1;
    }

}
