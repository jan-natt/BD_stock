<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuyerController extends Controller
{
     public function index()
    {
        return view('buyer.dashboard');
    }
}
