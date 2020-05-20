<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            $subject_id = $request->subject_identity;
            $user = Auth::User();

            $subject = DB::table('subjects')
                ->join('periods','subjects.period_id','=','periods.id')
                //->whereIn('periods.id', $auxArray)
                ->join('academic_years','periods.academic_year_id','=','academic_years.id')
                ->join('studies','academic_years.study_id','=','studies.id')
                ->where('subjects.id', $subject_id)
                ->orderBy('periods.start_date', 'desc')
                ->get(array(
                    'periods.id AS period_id',
                    'periods.name AS period_name',
                    'subjects.id AS subject_ID',
                    'subjects.name AS subject_name',
                    'subjects.color AS subject_color',
                    'studies.id AS study_id',
                    'academic_years.start_date AS start_year',
                    'academic_years.end_date AS end_year',
                ));
            $subjectArray = $subject->toArray();
            $subject= $subjectArray[0];

            /********************/
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
            /*******************/

            return view('user.subject',compact('subject','study','years'));
        }
        return view('auth.login');





    }

}
