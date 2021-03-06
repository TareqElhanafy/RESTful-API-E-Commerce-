<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('read-general')->only(['index','show']);
        $this->middleware('can:view,buyer')->only(['show']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyers=Buyer::has('transactions')->get();
        return $this->showAll($buyers);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        return $this->showOne($buyer);
    }

}
