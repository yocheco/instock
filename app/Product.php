<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
		'category_id',
		'user_id',
		'img',
		'description',
		'price',
		'address',
		'country',
		'state',
		'colony',
		'cp',
		'bathrooms',
		'ground',
		'available'
    ];

    protected $dates =['deleted_at'];

    public function setNameAttribute($valor){
        $this->attributes['name'] = strtolower($valor);
    }

    public function getNameAttribute($valor){
        return ucfirst($valor);
    }

    public function category(){
    	return $this->belongsTo(Category::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
