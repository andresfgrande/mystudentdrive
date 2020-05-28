<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
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
            $page_type = 'dashboard';
            $study = null;
            $subject = null;
            $hey = 0;
            //TODO ordenación de burbuja array

            /*************/
            $recentYearAux = $this->getRecentyear($user->getAuthIdentifier());

            if(empty($recentYearAux)){
                $recentYear = null;
            }else{
                $recentYear = $recentYearAux[0];
            }
            /*************/
            return view('dashboard',compact('planner_events','study','subject','page_type','hey','recentYear'));
        }
        //return view('dashboard');
    }

    public function getRecentyear($user_id){
        $result =  DB::table('academic_years')
            ->join('studies','academic_years.study_id','=','studies.id')
            ->join('users','studies.user_id','=','users.id')
            ->orderBy('academic_years.start_date','DESC')
            ->where('users.id','=',$user_id)
            ->take(1)
            ->get(array(
                'academic_years.id AS id',
                'academic_years.study_id AS study_id',
                'academic_years.start_date AS start_date',
                'academic_years.end_date AS end_date',
                'studies.id AS study_id',
                'studies.name AS study_name',
            ));
        $aux = $result->toArray();
        return $aux;
    }
}
