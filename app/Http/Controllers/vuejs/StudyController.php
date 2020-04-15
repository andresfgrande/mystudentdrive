<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class StudyController extends Controller
{
    public function getYearsByStudy(Request $request){

        $arrayIds = $request->get('studies');

        $aux = array();
        foreach($arrayIds as $id){
            $years =  DB::table('academic_years')->where('study_id', $id)->get();
            if(empty($years->toArray())){
                array_push($aux, ['vacio']);
            }else{
                array_push($aux, $years->toArray());
            }

        }

        return Response::json(array('success'=>true,'result'=>$aux));
    }

    public function getSubjectsByPeriod(Request $request){
        dd($request);
    }
}
