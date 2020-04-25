<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    public $table = "academic_years";
    public $fillable = ['study_id','start_date','end_date'];
}
