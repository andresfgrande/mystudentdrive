<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Planner extends Model
{
    public $table = "planner_events";
    public $fillable = ['user_id','subject_id','name','description','classroom','time','date','old_event'];
}
