<?php

namespace App\Http\Controllers\vuejs;

use App\AcademicYear;
use App\Http\Controllers\Controller;
use App\Study;
use App\Subject;
use App\Period;
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
        if(empty($arrayIds)){
            return Response::json(array('success'=>false,'result'=>'no_studies'));
        }

        $aux = array();
//        $aux2= array();
        foreach($arrayIds as $id){
            $years =  DB::table('academic_years')->where('study_id', $id)->get();
//            foreach($years as $year){
//                $aux2 = $year->start_date->format();
//            }

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
            ->join('academic_years','periods.academic_year_id','=','academic_years.id')
            ->join('studies','academic_years.study_id','=','studies.id')
            ->whereIn('periods.id', $auxArray)
            ->orderBy('periods.start_date', 'desc')
            ->get(array(
                'periods.id AS period_id',
                'periods.name AS period_name',
                'subjects.id AS subject_ID',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color',
                'studies.id AS study_id',
            ));

        return Response::json(array('success'=>true,'result'=>$result->toArray()));
    }
    /******** ADD STUDY ********/
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

    /******** ADD YEAR ********/
    public function addYear(Request $request){
        $study_id = $request->params['study_id'];
        $year_start = $request->params['year_start'];
        $year_end = $request->params['year_end'];

        $request->request->add(['study_id' => $request->params['study_id']]);
        $request->request->add(['year_start' => $request->params['year_start']]);
        $request->request->add(['year_end' => $request->params['year_end']]);
        try {
            $this->validate($request, [
                'study_id' => 'required',
                'year_start' => 'required',
                'year_end' => 'required'
            ]);
        } catch (ValidationException $e) {
            return Response::json(array('success'=>false,'result'=>'error_year_dates_required'));
        }

        if($year_start > $year_end){
            return Response::json(array('success'=>false,'result'=>'error_start_date_greater'));
        }else{
            $aux1 = $this->checkSolapaStartDate($study_id, $year_start);
            if($aux1){
                return Response::json(array('success'=>false,'result'=>'solapa_dates'));
            }
            $aux2 = $this->checkSolapaEndDate($study_id, $year_end);
            if($aux2){
                return Response::json(array('success'=>false,'result'=>'solapa_dates'));
            }
            $aux3 = $this->checkSolapaDates($study_id, $year_start, $year_end);
            if($aux3){
                return Response::json(array('success'=>false,'result'=>'solapa_dates'));
            }
            try {
                $year = new AcademicYear();
                $year->study_id = $study_id;
                $year->start_date = $year_start;
                $year->end_date = $year_end;
                $year->save();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>'error_create_year'));
            }
            return Response::json(array('success'=>true,'result'=>'year_created_ok'));
        }
    }

    /******** ADD SUBJECT ********/
    public function addSubject(Request $request){

        $period_id = $request->params['period_id'];
        $name = $request->params['name'];
        $color = $request->params['color'];

        $request->request->add(['period_id' => $request->params['period_id']]);
        $request->request->add(['name' => $request->params['name']]);
        $request->request->add(['color' => $request->params['color']]);
        try {
            $this->validate($request, [
                'period_id' => 'required',
                'name' => 'required',
                'color' => 'required'
            ]);
        } catch (ValidationException $e) {
            return Response::json(array('success'=>false,'result'=>'error_subject_required'));
        }

        $nameExist = $this->checkSubjectExistInPeriod($name, $period_id);
        if($nameExist){
            return Response::json(array('success'=>false,'result'=>'name_exists'));
        }

        try {
            $subject = new Subject();
            $subject->period_id = $period_id;
            $subject->name = $name;
            $subject->color = $color;
            $subject->save();
        } catch (\Throwable $e) {
            return Response::json(array('success'=>false,'result'=>'error_create_subject'));
        }
        return Response::json(array('success'=>true,'result'=>'subject_created_ok'));
    }

    /******** ADD PERIOD ********/
    public function addPeriod(Request $request){
        $year_id = $request->params['year_id'];
        $name = $request->params['name'];
        $start_date = $request->params['start_date'];
        $end_date = $request->params['end_date'];

        $request->request->add(['year_id' => $request->params['year_id']]);
        $request->request->add(['name' => $request->params['name']]);
        $request->request->add(['start_date' => $request->params['start_date']]);
        $request->request->add(['end_date' => $request->params['end_date']]);

        try {
            $this->validate($request, [
                'year_id' => 'required',
                'name' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);
        } catch (ValidationException $e) {
            return Response::json(array('success'=>false,'result'=>'error_period_required'));
        }

        $nameExist = $this->checkPeriodNameExist($year_id, $name);
        if($nameExist){
            return Response::json(array('success' => false, 'result' => 'period_name_exist'));
        }

        if($start_date > $end_date) {
            return Response::json(array('success' => false, 'result' => 'error_start_date_greater'));
        }else{
            $aux1 = $this->checkSolapaStartDatePeriod($year_id, $start_date);
            if($aux1){
                return Response::json(array('success'=>false,'result'=>'solapa_dates'));
            }
            $aux2 = $this->checkSolapaEndDatePeriod($year_id, $end_date);
            if($aux2){
                return Response::json(array('success'=>false,'result'=>'solapa_dates'));
            }
            $aux3 = $this->checkSolapaDatesPeriod($year_id, $start_date, $end_date);
            if($aux3){
                return Response::json(array('success'=>false,'result'=>'solapa_dates'));
            }
            /*********************/
            $periodInYearOk = $this->checkPeriodInYear($year_id, $start_date, $end_date);
            if(!$periodInYearOk){
                return Response::json(array('success'=>false,'result'=>'period_out_of_year'));
            }
            /*********************/
            try {
                $period = new Period();
                $period->academic_year_id = $year_id;
                $period->name = $name;
                $period->start_date = $start_date;
                $period->end_date = $end_date;
                $period->save();
                $data = DB::table('periods')
                    ->where('name', $name)
                    ->where('academic_year_id', $year_id)
                    ->get('id');
                $aux = $data->toArray();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>'error_create_period'));
            }
            return Response::json(array('success'=>true,'result'=>'period_created_ok','period'=>$aux));
        }
    }

    public function getPeriodsByYear(Request $request){
        $yearId = $request->get('year_id');
        $periods =  DB::table('periods')->where('academic_year_id', $yearId)->get();
        $periodsArray = $periods->toArray();

        return Response::json(array('success'=>true,'result'=>$periodsArray));
    }

    public function getStudiesAjax(){
        $user = Auth::User();
        $estudios =  DB::table('studies')->where('user_id', $user->id)->get();
        $arrayEstudios = $estudios->toArray();
        return Response::json(array('success'=>true,'result'=>$arrayEstudios));
    }

    public function checkSolapaStartDate($study_id, $year_start){
        $solapa_start =  DB::table('academic_years')
            ->where('study_id', $study_id)
            ->where('start_date','<=', $year_start)
            ->where('end_date','>=', $year_start)
            ->get('id');

        if(empty($solapa_start->toArray())){
            // no solapa
            return false;
        }
        return true;
    }

    public function checkSolapaEndDate($study_id, $year_end){
        $solapa_end =  DB::table('academic_years')
            ->where('study_id', $study_id)
            ->where('start_date','<=', $year_end)
            ->where('end_date','>=', $year_end)
            ->get('id');
        if(empty($solapa_end->toArray())){
            // no solapa
            return false;
        }
        return true;
    }

    public function checkSolapaDates($study_id, $year_start, $year_end){
        $solapa =  DB::table('academic_years')
            ->where('study_id', $study_id)
            ->where('start_date','>=', $year_start)
            ->where('end_date','<=', $year_end)
            ->get('id');
        if(empty($solapa->toArray())){
            // no solapa
            return false;
        }
        return true;
    }

    public function checkSubjectExistInPeriod($newName, $period_id){
        $data = DB::table('subjects')
            ->where('name', $newName)
            ->where('period_id', $period_id)
            ->get('id');
        if(empty($data->toArray())){
            // correcto, no existe
            return false;
        }
        return true;
    }

    public function checkPeriodInYear($year_id, $start_date, $end_date){
        $result =  DB::table('academic_years')
            ->where('id', $year_id)
            ->get(array(
                'id',
                'start_date',
                'end_date'
                ));

       $year = $result->toArray();

       if($start_date >= $year[0]->start_date && $end_date <= $year[0]->end_date){
           return true;
       }else{
           return false;
       }

    }

    public function checkSolapaStartDatePeriod($year_id, $year_start){
        $solapa_start =  DB::table('periods')
            ->where('academic_year_id', $year_id)
            ->where('start_date','<=', $year_start)
            ->where('end_date','>=', $year_start)
            ->get('id');

        if(empty($solapa_start->toArray())){
            // no solapa
            return false;
        }
        return true;
    }

    public function checkSolapaEndDatePeriod($year_id, $year_end){
        $solapa_end =  DB::table('periods')
            ->where('academic_year_id', $year_id)
            ->where('start_date','<=', $year_end)
            ->where('end_date','>=', $year_end)
            ->get('id');
        if(empty($solapa_end->toArray())){
            // no solapa
            return false;
        }
        return true;
    }

    public function checkSolapaDatesPeriod($year_id, $start_date, $end_date){
        $solapa =  DB::table('periods')
            ->where('academic_year_id', $year_id)
            ->where('start_date','>=', $start_date)
            ->where('end_date','<=', $end_date)
            ->get('id');
        if(empty($solapa->toArray())){
            // no solapa
            return false;
        }
        return true;
    }

    public function checkPeriodNameExist($year_id, $name){
        $result =  DB::table('periods')
            ->where('academic_year_id', $year_id)
            ->where('name', $name)
            ->get('id');

        if(empty($result->toArray())){
            //no existe
            return false;
        }
        return true;
    }
}
