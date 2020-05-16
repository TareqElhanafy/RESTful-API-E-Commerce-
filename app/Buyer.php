<?php

namespace App;

use App\Scopes\BuyerScope;

use Illuminate\Database\Eloquent\Model;
use App\Transaction;
class Buyer extends User
{
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }


       /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new BuyerScope);
    }
}
