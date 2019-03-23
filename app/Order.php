<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'quantity', 'total', 'cash', 'status', 'active', 'product_id', 'user_id'
    ];

    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = $request->active === '1' ? "true": "false";
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function credit(){
        return $this->belongsTo('App\Credit');
    }
}
