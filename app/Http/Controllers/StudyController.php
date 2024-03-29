<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudyController extends Controller
{
    public function index(){


        if (Auth::check()) {
            $user = Auth::User();
            $estudios =  DB::table('studies')->where('user_id', $user->id)->get();
            // The user is logged in...
            return view('user.studies',compact('estudios'));
        }
        return view('auth.login');
    }


}
