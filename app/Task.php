<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $table = "tasks";
    public $fillable = ['user_id','subject_id','description','date','is_urgent','is_done'];
}
