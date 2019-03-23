<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{ 
    // The attributes that are mass assignable.
    protected $fillable = [
        'number', 'csv', 'expiry'
    ];

    public function user(){
        return $this->hasOne('App\User');
    }
    
    public function orders(){
        return $this->hasMany('App\Order');
    }
}
