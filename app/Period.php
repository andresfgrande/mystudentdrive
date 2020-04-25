<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    public $table = "periods";
    public $fillable = ['academic_year_id','name','start_date','end_date'];
}
