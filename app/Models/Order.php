<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    public $guarded = [];

    public function user(){

        return $this->belongsTo(User::class);
    }
    public function payment(){

        return $this->hasOne(Payment::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
}
