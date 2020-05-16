<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transaction;
class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'quantity','status','seller_id','image'
    ];
protected $hidden=['pivot'];
    const UNAVAILABLE_PRODUCT='0';
    const AVAILABLE_PRODUCT='1';

    public function isAvailable(){
        return $this->status==='1';
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }
    public function seller(){
        return $this->belongsTo(Seller::class);
    }
}
