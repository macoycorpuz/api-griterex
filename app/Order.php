<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // The attributes that are mass assignable.
    protected $fillable = [
        'quantity', 'total', 'status', 'active', 'product_id', 'buyer_id', 'credit_id'
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function buyer(){
        return $this->belongsTo('App\User');
    }

    public function credit(){
        return $this->belongsTo('App\Credit');
    }
}
