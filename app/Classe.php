<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    public $table = "classes";
    public $fillable = ['subject_id','schedule_id','name','start_time','end_time','classroom'];
}
