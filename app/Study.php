<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    public $table = "studies";
    public $fillable = ['user_id','name'];
}
