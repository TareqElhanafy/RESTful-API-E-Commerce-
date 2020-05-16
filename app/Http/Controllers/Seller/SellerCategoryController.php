<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Seller;
use Illuminate\Http\Request;

class SellerCategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('read-general')->only(['index']);
        $this->middleware('can:view,seller')->only(['index']);


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $categories=$seller->products()->with('categories')
        ->get()
        ->pluck('categories')
        ->collapse();

        return $this->showAll($categories);
    }

}
