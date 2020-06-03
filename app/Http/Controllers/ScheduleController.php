<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index(){
        if (Auth::check()) {
            $user = Auth::User();
            $estudios =  DB::table('studies')->where('user_id', $user->getAuthIdentifier())->get();
            return view('user.schedules',compact('estudios'));
        }
        return view('auth.login');
    }
}
