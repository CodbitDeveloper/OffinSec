<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatrolAttendanceLine extends Model
{
    //
    protected $fillable = ["patrol_attendance_id", "guard_id", "reliever_id", "present", "applicable", "with_permission", "off_duty"];

    

    public function patrol_attendance()
    {
        return $this->belongsTo(PatrolAttendance::class);
    }


    public function security_guard()
    {
        return $this->belongsTo(Guard::class, "guard_id")->withTrashed();
    }

    public function reliever()
    {
        return $this->belongsTo(Guard::class, "reliever_id")->withTrashed();
    }
}
