<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Buyer;

class Transaction extends Model
{
    protected $fillable = [
        'name', 'product_id','buyer_id','quantity'
    ];

    public function buyer(){
        return $this->belongsTo(Buyer::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);

    }
}
