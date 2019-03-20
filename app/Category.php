<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // The attributes that are mass assignable.
    protected $fillable = [
        'name'
    ];
    
    public function products(){
        return $this->hasMany('App\Product');
    }
}
