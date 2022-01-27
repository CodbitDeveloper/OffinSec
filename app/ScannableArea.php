<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScannableArea extends Model
{
    //
    protected $fillable = ["name", "site_id", "location_code", "latitude", "longitude"];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
