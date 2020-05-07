<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudyViewController extends Controller
{
    public function index(Request $request){

        if (Auth::check()) {
            // The user is logged in...
            $var_study =  DB::table('studies')->where('id', $request->study_id)
                ->where('user_id',Auth::id())
                ->get();
            $aux = $var_study->toArray();
            $study = $aux[0];

            $var_years = DB::table('academic_years')
                ->where('study_id', $request->study_id)
                ->orderBy('start_date', 'desc')
                ->get();
            $aux2 = $var_years->toArray();
            $years = $aux2;

            return view('user.study',compact('study','years'));
        }
        return view('auth.login');
    }

}
