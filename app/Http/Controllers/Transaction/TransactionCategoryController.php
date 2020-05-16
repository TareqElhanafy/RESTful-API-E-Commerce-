<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['client.credentials'])->only(['index','show']);
        $this->middleware(['auth:api'])->except(['index','show']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        $categories=$transaction->product->categories;
        return $this->showAll($categories);
    }

}
