<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use App\Planner;
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
        $result = $result->toArray();
        $result2 =  DB::table('tasks')
            ->where('user_id',$user->getAuthIdentifier())
            ->orderBy('tasks.date','ASC')
            ->Where('subject_id','=',null)
            ->get(array(
                'tasks.id AS task_id',
                'tasks.description AS task_description',
                'tasks.date AS task_date',
                'tasks.is_urgent AS task_is_urgent',
                'tasks.is_done AS task_is_done',
            ));
        $result2 = $result2->toArray();
        $tasks = array_merge($result,$result2);
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

    public function editTask(Request $request){
        $task_id = $request->task['task_id'];
        $description = $request->task['description'];
        $date = $request->task['date'];
        $is_urgent = $request->task['is_urgent'];
        $subject_id = $request->task['subject_id'];

        try {
            $task = Task::find($task_id);
            $task->description = $description;
            $task->date = $date;
            $task->is_urgent = $is_urgent;
            $task->subject_id = $subject_id;
            $task->save();
        } catch (\Throwable $e) {
            return Response::json(array('success'=>false,'result'=>'error_edit_task'));
        }
        return Response::json(array('success'=>true,'result'=>'task_edited'));
    }

    public function deleteTask(Request $request){
        $task_id = $request->get('task_id');
        try {
            $task = Task::find($task_id);
            $task->forceDelete();
        } catch (\Throwable $e) {
            return Response::json(array('success'=>false,'result'=>'error_delete_task'));
        }
        return Response::json(array('success'=>true,'result'=>'task_deleted'));
    }

    public function taskDone(Request $request){
        $task_id = $request->task['task_id'];

        try {
            $task = Task::find($task_id);
            if($task->is_done == 0){
                $done = true;
            }else{
                $done = false;
            }
            $task->is_done = $done;
            $task->save();
        } catch (\Throwable $e) {
            return Response::json(array('success'=>false,'result'=>'error_task_to_done'));
        }
        return Response::json(array('success'=>true,'result'=>'task_done'));
    }

}
