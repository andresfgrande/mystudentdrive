<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use App\Planner;
use App\Subject;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class PlannerController extends Controller
{
    public function addEvent(Request $request){
        $subject_id = $request->event_planner['subject_id'];
        $name = $request->event_planner['name'];
        $description = $request->event_planner['description'];
        $classroom = $request->event_planner['classroom'];
        $time = $request->event_planner['time'];
        $date = $request->event_planner['date'];
        //$old_event = $request->event_planner['old_event'];
        $user = Auth::User();
        $user_id = $user->getAuthIdentifier();


        $request->request->add(['name' => $request->event_planner['name']]);
        $request->request->add(['time' => $request->event_planner['time']]);
        $request->request->add(['date' => $request->event_planner['date']]);

        try {
            $this->validate($request, [
                'name' => 'required',
                'time' => 'required',
                'date' => 'required',
            ]);
        } catch (ValidationException $e) {
            return Response::json(array('success'=>false,'result'=>'error_event_data_required'));
        }

        $result =  DB::table('planner_events')
            ->where('user_id', $user_id)
            ->where('name',$name)
            ->where('subject_id',$subject_id)
            ->get('id');

        if( empty($result->toArray())){
            try {
                $event = new Planner();
                $event->user_id = $user_id;
                $event->name = $name;
                $event->description = $description;
                $event->classroom = $classroom;
                $event->time = $time;
                $event->date = $date;
                $event->old_event = false;
                $event->subject_id = $subject_id;
                $event->save();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>'error_create_event'));
            }
        }else{
            return Response::json(array('success'=>false,'result'=>'error_event_exists'));
        }
        return Response::json(array('success'=>true,'result'=>'event_created'));
    }

    public function getEvents(Request $request){
        $show_old = $request->get('show_old');

        $user = Auth::User();
        if($show_old == 'true'){
            $planner_events = $this->queryGetAllEvents($user);
        }else{
            $planner_events = $this->queryGetCurrentEvents($user);
        }
        return Response::json(array('success'=>true,'result'=>$planner_events));
    }

    public function getSubjectsByUser(Request $request){
        $user = Auth::User();
        $result =  DB::table('subjects')
            ->join('periods','subjects.period_id','=','periods.id')
            ->join('academic_years','periods.academic_year_id','=','academic_years.id')
            ->join('studies','academic_years.study_id','=','studies.id')
            ->join('users','studies.user_id','=','users.id')
            ->where('users.id',$user->getAuthIdentifier())
            ->orderBy('periods.start_date','DESC')
            ->get(array(
                'subjects.id AS subject_id',
                'subjects.name AS subject_name',
                'periods.name AS period_name',
                'studies.name AS study_name'
            ));
        $subjects = $result->toArray();
        return Response::json(array('success'=>true,'result'=>$subjects));
    }

    public function editEvent(Request $request){

        $event_id = $request->event_planner['event_id'];
        $subject_id = $request->event_planner['subject_id'];
        $name = $request->event_planner['name'];
        $description = $request->event_planner['description'];
        $classroom = $request->event_planner['classroom'];
        $time = $request->event_planner['time'];
        $date = $request->event_planner['date'];
        $user = Auth::User();
        $user_id = $user->getAuthIdentifier();

        $request->request->add(['name' => $request->event_planner['name']]);
        $request->request->add(['time' => $request->event_planner['time']]);
        $request->request->add(['date' => $request->event_planner['date']]);

        try {
            $this->validate($request, [
                'name' => 'required',
                'time' => 'required',
                'date' => 'required',
            ]);
        } catch (ValidationException $e) {
            return Response::json(array('success'=>false,'result'=>'error_event_data_required'));
        }

        $result =  DB::table('planner_events')
            ->where('user_id', $user_id)
            ->where('name',$name)
            ->where('subject_id',$subject_id)
            ->get('id');

        $aux = DB::table('planner_events')
            ->where('id',$event_id)
            ->get('name');
        $aux = $aux->toArray();
        $canEdit = false;
        if($aux[0]->name == $name){
            $canEdit = true;
        }

        if(empty($result->toArray()) || $canEdit){
            try {
                $event = Planner::find($event_id);
                $event->user_id = $user_id;
                $event->name = $name;
                $event->description = $description;
                $event->classroom = $classroom;
                $event->time = $time;
                $event->date = $date;
                $event->old_event = false;
                $event->subject_id = $subject_id;
                $event->save();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>'error_edit_event'));
            }
        }else{
            return Response::json(array('success'=>false,'result'=>'error_event_exists'));
        }
        return Response::json(array('success'=>true,'result'=>'event_edited'));
    }

    public function deleteEvent(Request $request){
       $event_id = $request->get('event_id');
        try {
            $event = Planner::find($event_id);
            $event->forceDelete();
        } catch (\Throwable $e) {
            return Response::json(array('success'=>false,'result'=>'error_delete_event'));
        }
        return Response::json(array('success'=>true,'result'=>'event_deleted'));
    }

    public function queryGetAllEvents($user){
        $result =  DB::table('planner_events')
            ->join('subjects','planner_events.subject_id','=','subjects.id')
            ->where('user_id', $user->getAuthIdentifier())
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
                'subjects.period_id AS subject_period_id',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color',
            ));
        $planner_events1 = $result->toArray();
        $result2 =  DB::table('planner_events')
            ->where('user_id', $user->getAuthIdentifier())
            ->Where('subject_id','=',null)
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
            ));
        $planner_events2 = $result2->toArray();
        $planner_events = array_merge($planner_events1,$planner_events2);

        return $planner_events;
    }

    public function queryGetCurrentEvents($user){
        $result =  DB::table('planner_events')
            ->join('subjects','planner_events.subject_id','=','subjects.id')
            ->where('user_id', $user->getAuthIdentifier())
            ->where('old_event','=' ,false)//check old
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
                'subjects.period_id AS subject_period_id',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color',
            ));
        $planner_events1 = $result->toArray();
        $result2 =  DB::table('planner_events')
            ->where('user_id', $user->getAuthIdentifier())
            ->Where('subject_id','=',null)
            ->where('old_event','=' ,false)//check old
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
            ));
        $planner_events2 = $result2->toArray();
        $planner_events = array_merge($planner_events1,$planner_events2);

        return $planner_events;
    }

    public function updateOldEvents(Request $request){

        $user = Auth::User();
        $user_id = $user->getAuthIdentifier();

        $result =  DB::table('planner_events')
            ->where('user_id', $user_id)
            ->where('old_event','=',0)
            ->get();
        $result = $result->toArray();

        $currentDate = date('Y-m-d');
        foreach($result as $event){
            if($event->date < $currentDate){
                $event = Planner::find($event->id);
                $event->old_event = true;
                $event->save();
            }
        }

    }

    public function getEventsByStudy(Request $request){
        $show_old = $request->get('show_old');
        $study_id= $request->get('study_id');

        $user = Auth::User();
        if($show_old == 'true'){
            $planner_events = $this->queryGetAllEventsByStudy($user, $study_id);
        }else{
            $planner_events = $this->queryGetCurrentEventsByStudy($user,$study_id);
        }
        return Response::json(array('success'=>true,'result'=>$planner_events));
    }

    public function queryGetAllEventsByStudy($user, $study_id){
        $result =  DB::table('planner_events')
            ->join('subjects','planner_events.subject_id','=','subjects.id')
            ->join('periods','subjects.period_id','=','periods.id')
            ->join('academic_years','periods.academic_year_id','=','academic_years.id')
            ->join('studies','academic_years.study_id','=','studies.id')
            ->where('studies.id', $study_id)
            ->where('planner_events.user_id', $user->getAuthIdentifier())
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
                'subjects.period_id AS subject_period_id',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color',
            ));
        $planner_events1 = $result->toArray();
        $result2 =  DB::table('planner_events')
            ->where('user_id', $user->getAuthIdentifier())
            ->Where('subject_id','=',null)
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
            ));
        $planner_events2 = $result2->toArray();
        $planner_events = array_merge($planner_events1,$planner_events2);

        return $planner_events;
    }

    public function queryGetCurrentEventsByStudy($user, $study_id){
        $result =  DB::table('planner_events')
            ->join('subjects','planner_events.subject_id','=','subjects.id')
            ->join('periods','subjects.period_id','=','periods.id')
            ->join('academic_years','periods.academic_year_id','=','academic_years.id')
            ->join('studies','academic_years.study_id','=','studies.id')
            ->where('studies.id', $study_id)
            ->where('planner_events.user_id', $user->getAuthIdentifier())
            ->where('old_event','=' ,false)//check old
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
                'subjects.period_id AS subject_period_id',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color',
            ));
        $planner_events1 = $result->toArray();
        $result2 =  DB::table('planner_events')
            ->where('user_id', $user->getAuthIdentifier())
            ->Where('subject_id','=',null)
            ->where('old_event','=' ,false)//check old
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
            ));
        $planner_events2 = $result2->toArray();
        $planner_events = array_merge($planner_events1,$planner_events2);

        return $planner_events;
    }

    public function getEventsBySubject(Request $request){
        $show_old = $request->get('show_old');
        $subject_id= $request->get('subject_id');

        $user = Auth::User();
        if($show_old == 'true'){
            $planner_events = $this->queryGetAllEventsBySubject($user, $subject_id);
        }else{
            $planner_events = $this->queryGetCurrentEventsBySubject($user,$subject_id);
        }
        return Response::json(array('success'=>true,'result'=>$planner_events));
    }

    public function queryGetAllEventsBySubject($user, $subject_id){
        $result =  DB::table('planner_events')
            ->join('subjects','planner_events.subject_id','=','subjects.id')
            ->where('planner_events.subject_id', $subject_id)
            ->where('planner_events.user_id', $user->getAuthIdentifier())
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
                'subjects.period_id AS subject_period_id',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color',
            ));
        $planner_events = $result->toArray();

        return $planner_events;
    }

    public function queryGetCurrentEventsBySubject($user, $subject_id){
        $result =  DB::table('planner_events')
            ->join('subjects','planner_events.subject_id','=','subjects.id')
            ->where('planner_events.subject_id', $subject_id)
            ->where('planner_events.user_id', $user->getAuthIdentifier())
            ->where('old_event','=' ,false)//check old
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
                'subjects.period_id AS subject_period_id',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color',
            ));
        $planner_events = $result->toArray();

        return $planner_events;
    }

    public function getEventsByDate(Request $request){

        $date = $request->get('info_date');

        $date =  new DateTime($date);
        $date->modify('+1 day');

        $user = Auth::User();
        $planner_events = $this->queryGetAllEventsByDate($user, $date);
        return Response::json(array('success'=>true,'result'=>$planner_events));
    }
    public function queryGetAllEventsByDate($user,$date){
        $result =  DB::table('planner_events')
            ->join('subjects','planner_events.subject_id','=','subjects.id')
            ->where('user_id', $user->getAuthIdentifier())
            ->where('date',$date)
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
                'subjects.period_id AS subject_period_id',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color',
            ));
        $planner_events1 = $result->toArray();
        $result2 =  DB::table('planner_events')
            ->where('user_id', $user->getAuthIdentifier())
            ->Where('subject_id','=',null)
            ->where('date',$date)
            ->orderBy('date','ASC')
            ->get(array(
                'planner_events.id AS id',
                'planner_events.user_id AS user_id',
                'planner_events.subject_id AS subject_id',
                'planner_events.name AS name',
                'planner_events.description AS description',
                'planner_events.classroom AS classroom',
                'planner_events.time AS time',
                'planner_events.date AS date',
                'planner_events.old_event AS old_event',
            ));
        $planner_events2 = $result2->toArray();
        $planner_events = array_merge($planner_events1,$planner_events2);

        return $planner_events;
    }
}
