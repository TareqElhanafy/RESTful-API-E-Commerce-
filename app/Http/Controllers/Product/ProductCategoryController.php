<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['client.credentials'])->only(['index','show']);
        $this->middleware(['auth:api'])->except(['index','show']);
        $this->middleware(['can:delete,product'])->only(['destroy']);
        $this->middleware(['can:update,product'])->only(['update']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories=$product->categories;

        return $this->showAll($categories);
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product,Category $category)
    {
        $product->categories()->syncWithoutDetaching($category->id);
        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product , Category $category)
    {
        if (!$product->categories()->find($category->id)) {
           return $this->showMessage(['message'=>'this catgory is not related to this product'],409); 
        }
        $product->categories()->detach($category->id);
        return $this->showAll($product->categories);
        
   }
}
