<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ScheduleController extends Controller
{
    public function getSchedulesByPeriod(Request $request){
        $period_id = $request->get('period_id');
        $user = Auth::User();

        $result =  DB::table('schedules')
            ->where('period_id','=',$period_id)
            ->get();
        $result = $result->toArray();
        //dd($result[0]->id);

        return Response::json(array('success'=>true,'result'=>$result));
    }

    public function getClassesByScheduleAndDay(Request $request){
        $schedule_id = $request->get('schedule_id');

        $classes = $this->getClassesBySchedule($schedule_id);

        return Response::json(array('success'=>true,'result'=>$classes));
    }

    public function getClassesBySchedule($schedule_id){
        $classesHorario = array(
            'monday'=>[],
            'tuesday'=>[],
            'wednesday'=>[],
            'thursday'=>[],
            'friday'=>[],
        );

        $classesHorario['monday'] = $this->getClassesByDayAndSchedule('mon',$schedule_id);
        $classesHorario['tuesday']= $this->getClassesByDayAndSchedule('tue',$schedule_id);
        $classesHorario['wednesday']= $this->getClassesByDayAndSchedule('wed',$schedule_id);
        $classesHorario['thursday']= $this->getClassesByDayAndSchedule('thu',$schedule_id);
        $classesHorario['friday']= $this->getClassesByDayAndSchedule('fri',$schedule_id);

        return $classesHorario;

    }

    public function getClassesByDayAndSchedule($day, $schedule_id){
        $result = DB::table('classes')
            ->join('days','days.class_id','=', 'classes.id')
            ->join('subjects','subjects.id','=','classes.subject_id')
            ->where('schedule_id','=',$schedule_id)
            ->where($day,'=',true)
            ->orderBy('start_time','ASC')
            ->get(array(
                'classes.id AS class_id',
                'classes.subject_id AS class_subject_id',
                'classes.schedule_id AS class_schedule_id',
                'classes.name AS class_name',
                'classes.start_time AS class_start_time',
                'classes.end_time AS class_end_time',
                'classes.classroom AS class_classroom',
                'subjects.id AS subject_id',
                'subjects.period_id AS subject_period_id',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color'
            ));
        return $result->toArray();
    }

    public function getRecentScheduleByUser(Request $request){
        $user = Auth::User();
        $user_id = $user->getAuthIdentifier();

        $result = DB::table('schedules')
            ->join('periods','periods.id','=','schedules.period_id')
            ->join('academic_years','academic_years.id','=','periods.academic_year_id')
            ->join('studies','studies.id','=','academic_years.study_id')
            ->join('users','users.id','=','studies.user_id')
            ->orderBy('periods.start_date','DESC')
            ->where('users.id','=',$user_id)
            ->take(1)
            ->get(array(
                'schedules.id AS schedule_id',
                'schedules.period_id AS schedule_period_id',
                'schedules.name AS schedule'
            ));

        $schedule = $result->toArray();
        return Response::json(array('success'=>true,'result'=>$schedule));

    }
}
