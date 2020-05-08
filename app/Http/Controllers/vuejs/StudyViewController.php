<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class StudyViewController extends Controller
{
    public function getYearsByOneStudy(Request $request){

        $study_id = $request->get('study_id');

        $var_years = DB::table('academic_years')
            ->where('study_id', $study_id)
            ->orderBy('start_date', 'desc')
            ->get();
        $years = $var_years->toArray();

        return Response::json(array('success'=>true,'result'=>$years));
    }
}
