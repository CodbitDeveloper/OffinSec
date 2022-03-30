<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    //
    protected $fillable = ["name", "user_id"];

    public function sites(){
        return $this->hasMany(Site::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
