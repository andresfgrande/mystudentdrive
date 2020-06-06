<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public $table = "schedules";
    public $fillable = ['period_id','name'];
}
