<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryTransactionController extends Controller
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
    public function index(Category $category)
    {
      $transactions=$category->products()->whereHas('transactions')
      ->with('transactions')
        ->get()
        ->pluck('transactions');

        return $this->showAll($transactions);
    }

}
