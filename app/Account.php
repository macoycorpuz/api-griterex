<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    // The attributes that are mass assignable.
    protected $fillable = [
        'name'
    ];
    
    public function users(){
        return $this->hasMany('App\User');
    }
}
