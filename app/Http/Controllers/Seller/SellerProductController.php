<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use App\Seller;
use Illuminate\Support\Facades\Storage;

class SellerProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:manage-product')->except(['index']);
        $this->middleware('can:view,seller')->only(['index']);
        $this->middleware('can:edit,seller')->only(['update']);
        $this->middleware('can:delete,seller')->only(['destroy']);
        $this->middleware('can:sale,seller')->only(['store']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        if (request()->user()->tokenCan('read-general')||request()->user()->tokenCan('manage-products')) {
            $products=$seller->products;
            return $this->showAll($products);   
             }
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Seller $seller)
    {
        $rules=[
            'name'=>'required',
            'description'=>'required',
            'quantity'=>'required|integer|min:1',
            'image'=>'required|image',
            
        ];

        $this->validate($request,$rules);
        $data=$request->all();
        $data['image']=$request->image->store('products');
        $data['status']=Product::UNAVAILABLE_PRODUCT;
        $data['seller_id']=$seller->id;
        $product=Product::create($data);
        return $this->showOne($product);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller,Product $product)
    {
        $rules=[
            'quantity'=>'min:1|integer',
            'image'=>'image'
        ];

        $this->validate($request,$rules);

        if ($seller->id!==$product->seller_id) {
            return $this->errorResponse(['error'=>'Sorry only tho owner can edit the product'],409);
            }

        $data=$request->only(['name','description','quantity']);


if ($request->has('status')) {
    $product->status=$request->status;

       if ($product->isAvailable()&&$product->categories->count()==0) {
         return $this->errorResponse(['error'=>'This product has no categories'],409);
  
       }
    }
    if ($request->hasFile('image')) {
        Storage::delete($product->id);
        $image=$request->image->store('products');
        $data['image']=$image;
    }
    // if ($product->isClean()) {
    //     return $this->errorResponse(['error'=>'please enter the specified data'],409);
    // }

    $product->update($data);
    return $this->showOne($product);

}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller ,Product $product)
    {
        if ($seller->id!==$product->seller_id) {
            return $this->errorResponse(['error'=>'Sorry only tho owner can delete the product'],409);
            }
            $product->delete();
            return $this->showOne($product);
    }


   
}
