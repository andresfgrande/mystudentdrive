<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TaskController extends Controller
{
    public function getAllTasks(Request $request){
        $user = Auth::User();
        $result =  DB::table('tasks')
            ->join('subjects','tasks.subject_id','=','subjects.id')
            ->where('user_id',$user->getAuthIdentifier())
            ->orderBy('tasks.date','ASC')
            ->get(array(
                'tasks.id AS task_id',
                'tasks.description AS task_description',
                'tasks.date AS task_date',
                'tasks.is_urgent AS task_is_urgent',
                'tasks.is_done AS task_is_done',
                'subjects.id AS subject_id',
                'subjects.name AS subject_name',
                'subjects.color AS subject_color',

            ));
        $tasks = $result->toArray();
        return Response::json(array('success'=>true,'result'=>$tasks));
    }

    public function addTask(Request $request){

        $user = Auth::User();
        $user_id = $user->getAuthIdentifier();
        $subject_id = $request->task['subject_id'];
        $description = $request->task['description'];
        $date = $request->task['date'];
        $is_urgent = $request->task['is_urgent'];
        try {
            $task = new Task();
            $task->user_id = $user_id;
            $task->subject_id = $subject_id;
            $task->description = $description;
            $task->date = $date;
            $task->is_urgent = $is_urgent;
            $task->is_done = false;
            $task->save();
        } catch (\Throwable $e) {
            return Response::json(array('success'=>false,'result'=>'error_create_task'));
        }
        return Response::json(array('success'=>true,'result'=>'task_created_ok'));
    }

    public function editTask(){
        //Todo
    }

    public function removeTask(){
        //todo
    }


}
