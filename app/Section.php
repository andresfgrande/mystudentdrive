<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public $table = "sections";
    public $fillable = ['subject_id','name'];
}
