<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotels;
use App\Models\country;
use App\Http\Middleware\ClientMiddleware;

class ClientController extends Controller
{
    //
    public function __construct()
  { 
     
      $this->middleware(ClientMiddleware::class);
  }
   

}