<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    public $table = "days";
    public $fillable = ['class_id','mon','tue','wed','thu','fri','sat','sun'];
}
