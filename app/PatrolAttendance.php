<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatrolAttendance extends Model
{
    //
    protected $fillable = ["user_id", "site_id"];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patrol_officer()
    {
        return $this->belongsTo(User::class);
    }

    public function lines()
    {
        return $this->hasMany(PatrolAttendanceLine::class);
    }
}
