<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuyerProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('read-general')->only(['index']);
        $this->middleware('can:view,buyer')->only(['index']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $products=$buyer->transactions()->with('product')
        ->get()
        ->pluck('product')
        ->unique('id')
        ->values();
        return $this->showAll($products);
    }

}
