<?php

namespace App\Http\Controllers\vuejs;

use App\Classe;
use App\Day;
use App\Http\Controllers\Controller;
use App\Schedule;
use App\Study;
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
                'schedules.name AS schedule',
                'academic_years.id AS year_id',
                'studies.name AS study_name'
            ));

        $schedule = $result->toArray();
        return Response::json(array('success'=>true,'result'=>$schedule));

    }

    public function addSchedule(Request $request){
        $period_id = $request->params['period_id'];
        $name = $request->params['name'];

        $result =  DB::table('schedules')
            ->where('period_id', $period_id)
            ->where('name', $name)
            ->get('id');

        if( empty($result->toArray())){
            try {
                $schedule = new Schedule();
                $schedule->period_id = $period_id;
                $schedule->name = $name;
                $schedule->save();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>'error_create_schedule'));
            }
        }else{
            //ya existe este horario para este periodo
            return Response::json(array('success'=>false,'result'=>'error_schedule_exists'));
        }
        return Response::json(array('success'=>true,'result'=>'schedule_created'));
    }

    public function addClasse(Request $request){
        $subject_id = $request->params['subject_id'];
        $schedule_id = $request->params['schedule_id'];
        $name = $request->params['name'];
        $start_time = $request->params['start_time'];
        $end_time = $request->params['end_time'];
        $classroom = $request->params['classroom'];
        $monday = $request->params['monday'];
        $tuesday = $request->params['tuesday'];
        $wednesday = $request->params['wednesday'];
        $thursday = $request->params['thursday'];
        $friday = $request->params['friday'];


        //TODO CHEck name existe
        try {
            $classe = new Classe();
            $classe->subject_id = $subject_id;
            $classe->schedule_id = $schedule_id;
            $classe->name = $name;
            $classe->start_time = $start_time;
            $classe->end_time = $end_time;
            $classe->classroom = $classroom;
            $classe->save();
        } catch (\Throwable $e) {
            return Response::json(array('success'=>false,'result'=>'error_create_classe'));
        }

        $result =  DB::table('classes')
            ->where('name', $name)
            ->where('schedule_id', $schedule_id)
            ->where('subject_id', $subject_id)
            ->get('id');
        $class_id =$result->toArray();

        try {
            $day = new Day();
            $day->class_id = $class_id[0]->id;
            $day->mon = $monday;
            $day->tue= $tuesday;
            $day->wed = $wednesday;
            $day->thu = $thursday;
            $day->fri = $friday;
            $day->sat = false;
            $day->sun = false;
            $day->save();
        } catch (\Throwable $e) {
            return Response::json(array('success'=>false,'result'=>$e));
        }
        return Response::json(array('success'=>true,'result'=>'classe_created'));
    }

    public function getSubjectsByPeriod(Request $request){
        $period_id = $request->get('period_id');

        $result =  DB::table('subjects')
            ->where('period_id', $period_id)
            ->get();

        $subjects = $result->toArray();
        return Response::json(array('success'=>true,'result'=>$subjects));
    }
}
