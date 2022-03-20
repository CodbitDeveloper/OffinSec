<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guard extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'guards';

    public $incrementing  = false;

    protected $fillable = [
        'firstname',
        'lastname',
        'dob',
        'gender',
        'marital_status',
        'occupation',
        'address',
        'national_id',
        'id_type',
        'phone_number',
        'SSNIT',
        'emergency_contact',
        'photo',
        'bank_name',
        'bank_branch',
        'account_number',
        'guard_number'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($guard) {
            $guard->guard_number = "OSO" . rand(100, 999) . rand(100, 999) . rand(10, 99) . strtoupper(substr($guard->firstname, 0, 2)) . strtoupper(substr($guard->lastname, 0, 2));
        });
    }

    public function role()
    {
        return $this->belongsTo('App\Role', 'occupation');
    }

    public function fingerprint()
    {
        return $this->hasOne('App\Fingerprint');
    }

    public function guarantors()
    {
        return $this->hasMany('App\Guarantor', 'guard_id');
    }

    public function duty_rosters()
    {
        return $this->belongsToMany('App\Duty_Roster', 'guard_roster', 'guard_id', 'duty_roster_id')
            ->withPivot('shift_type_id', 'day')
            ->join('shift_types', 'shift_type_id', '=', 'shift_types.id')
            ->select('duty_rosters.*', 'shift_types.name as pivot_shift_type_name', 'shift_types.start_time as pivot_shift_type_start_time', 'shift_types.end_time as pivot_shift_type_end_time')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany('App\Attendance', 'guard_id');
    }

    public function attendance_requests()
    {
        return $this->hasMany('App\AttendanceRequests', 'guard_id');
    }

    public function deductions()
    {
        return $this->belongsToMany('App\Deduction', 'deduction_guard')->withPivot('date', 'details', 'amount')->withTimestamps();
    }

    public function permissions()
    {
        return $this->hasMany('App\Permission', 'guard_id');
    }

    public function sites()
    {
        return $this->hasMany('App\Site', 'guard_id');
    }

    public function salary()
    {
        return $this->hasMany('App\Salary');
    }

    public function client_salary()
    {
        return $this->hasMany('App\ClientSalary');
    }
}
