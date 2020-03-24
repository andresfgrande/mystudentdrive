<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(){

        if (Auth::check()) {
            // The user is logged in...
           // return view('home');
            return redirect('home');
        }
        return view('welcome');
    }
}
