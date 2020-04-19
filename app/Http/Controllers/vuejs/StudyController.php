<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use App\Study;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    public function getSubjectsByYear(Request $request){
        $yearId = $request->get('year');

        $periods =  DB::table('periods')->where('academic_year_id', $yearId)->get('id');
        $periodsArray = $periods->toArray();

        $auxArray = array();
        foreach($periodsArray as $period){
         array_push($auxArray,$period->id);
        }

        $result = DB::table('subjects')
            ->join('periods','subjects.period_id','=','periods.id')
            ->whereIn('periods.id', $auxArray)
            ->get(array(
                'periods.id AS period_id',
                'periods.name AS period_name',
                'subjects.id AS subject_ID',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color',
            ));

        return Response::json(array('success'=>true,'result'=>$result->toArray()));
    }

    public function addStudy(Request $request){
        $user = Auth::User();
        $user_id = $user->getAuthIdentifier();
        $request->request->add(['user_id' => $user_id]);

        $result =  DB::table('studies')
            ->where('user_id', $user_id)
            ->where('name',$request->get('study'))
            ->get('id');

        try {
            $this->validate($request, [
                'study' => 'required',
                'user_id' => 'required'
            ]);
        } catch (ValidationException $e) {
            return Response::json(array('success'=>false,'result'=>'error_name_required'));
        }

        if( empty($result->toArray())){
            //correcto, no existe estudio con ese nombre
            try {
                $study = new Study();
                $study->user_id = $user_id;
                $study->name = $request->study;
                $study->save();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>'error_create_study'));
            }
        }else{
            //ya existe este estudio para este usuario
            return Response::json(array('success'=>false,'result'=>'error_study_exists'));
        }


        return Response::json(array('success'=>true,'result'=>'study_created'));
    }

    public function addPeriod(Request $request){

    }

    public function addSubject(Request $request){

    }

    public function getStudiesAjax(){
        $user = Auth::User();
        $estudios =  DB::table('studies')->where('user_id', $user->id)->get();
        $arrayEstudios = $estudios->toArray();
        return Response::json(array('success'=>true,'result'=>$arrayEstudios));
    }
}
