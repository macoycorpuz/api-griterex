<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    // The attributes that are mass assignable.
    protected $fillable = [
        'account_id', 'name', 'email', 'password', 'number', 'address', 'lat', 'lng'
    ];

    public function credit(){
        return $this->belongsTo('App\Credit');
    }
    
    public function account(){
        return $this->belongsTo('App\Account');
    }
}
