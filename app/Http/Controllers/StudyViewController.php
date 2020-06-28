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

            $chosenYearStudies = $request->get('year');

            $var_study =  DB::table('studies')->where('id', $request->study_id)
                ->where('user_id',Auth::id())
                ->get();
            $aux = $var_study->toArray();

            if(isset($aux[0])){
                $study = $aux[0];
            }else{
                return back();
            }

            $var_years = DB::table('academic_years')
                ->where('study_id', $request->study_id)
                ->orderBy('start_date', 'desc')
                ->get();
            $aux2 = $var_years->toArray();
            $years = $aux2;

            $indexChosenYear = null;
            foreach($years as $key=>$year){
                if($year->id ==  $chosenYearStudies ){
                    $indexChosenYear = $key;
                }
            }
            if($indexChosenYear == null){
                $indexChosenYear = 0;
            }
            $page_type = 'study';
            $subject = null;
            return view('user.study',compact('study','years','indexChosenYear','page_type','subject'));
        }
        return view('auth.login');
    }

}
