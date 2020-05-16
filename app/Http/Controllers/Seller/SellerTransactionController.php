<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Seller;
use Illuminate\Http\Request;

class SellerTransactionController extends Controller
{
  public function __construct()
  {
      parent::__construct();
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
      $transactions=$seller->products()->whereHas('transactions')->with('transactions')
      ->get()
      ->pluck('transactions')
      ->collapse();
      return $this->showAll($transactions);
    }

}
