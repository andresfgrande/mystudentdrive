<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public $table = "subjects";
    public $fillable = ['period_id','name','color'];
}
