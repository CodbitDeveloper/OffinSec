<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift_Type extends Model
{
    use SoftDeletes;
    protected $table = "shift_types";

    protected $fillable = [
        'name'
    ];

    public function attendances()
    {
        return $this->hasMany('App\Attendance');
    }

}
