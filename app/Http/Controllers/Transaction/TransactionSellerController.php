<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionSellerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('read-general')->only(['index']);
        $this->middleware('can:view,transaction')->only(['index']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        $seller=$transaction->product->seller;
        return $this->showOne($seller);
    }

}
