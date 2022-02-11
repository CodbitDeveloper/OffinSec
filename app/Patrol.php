<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patrol extends Model
{
    //
    protected $fillable = ["site_id", "patrol_officer", "notes", "user_id"];

    public function scans()
    {
        return $this->belongsToMany(ScannableArea::class, "patrol_scan")->withPivot("created_at", "latitude", "longitude");
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function images()
    {
        return $this->hasMany(PatrolImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
