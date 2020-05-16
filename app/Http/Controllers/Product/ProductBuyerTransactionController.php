<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Product;
use App\Transaction;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:purchase-product')->only('store');
        $this->middleware('can:purchase,buyer')->only(['store']);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product,User $buyer)
    {
        $rules=[
            'quantity'=>'required|integer|min:1'
        ];

        $this->validate($request,$rules);
        
        if (!$product->isAvailable()) {
            return $this->errorResponse(['error'=>'this product is not available !'],409);
        }
       
        if (!$buyer->isVerified()) {
            return $this->errorResponse(['error'=>'you are  not verified'],409);

        }
        if (!$product->seller->isVerified()) {
            return $this->errorResponse(['error'=>'sorry the owner is restricted'],409);

        }
     
        if ($request->quantity > $product->quantity) {
            return $this->errorResponse(['error'=>'sorry the product is limited'],409);
        }
        
       return DB::transaction(function() use ($request,$product,$buyer){

            $product->quantity-=$request->quantity;
            $product->save();
            $transaction=Transaction::create([
                'buyer_id'=>$buyer->id,
                'product_id'=>$product->id,
                'quantity'=>$request->quantity
            ]);
            return $this->showOne($transaction);
        });


    }

}
