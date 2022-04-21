<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallCheck extends Model
{
    //
    protected $fillable = ["user_id", "site_id", "report"];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
