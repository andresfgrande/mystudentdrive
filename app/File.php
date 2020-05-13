<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $table = "files";
    public $fillable = ['section_id','name','file_path','access_code'];
}
