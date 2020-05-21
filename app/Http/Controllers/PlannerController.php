<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlannerController extends Controller
{
    public function index(){
        if (Auth::check()) {
            $user = Auth::User();

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
                ->where('old_event', '=',false) //check old
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

            //TODO ordenación de burbuja array
            return view('user.planner',compact('planner_events'));
        }
        return view('auth.login');
    }
}
