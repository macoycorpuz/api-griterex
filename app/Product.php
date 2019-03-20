<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // The attributes that are mass assignable.
    protected $fillable = [
        'name', 'description', 'price', 'url', 'supplier_id', 'category_id'
    ];

    public function suppliers(){
        return $this->hasMany('App\User');
    }
    
    public function categories(){
        return $this->hasMany('App\Categories');
    }
}
