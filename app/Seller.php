<?php

namespace App;
use App\Product;
use App\User;
use App\Scopes\SellerScope;

use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    public function products(){
        return $this->hasMany(Product::class);
    }


    
       /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new SellerScope);
    }

}
