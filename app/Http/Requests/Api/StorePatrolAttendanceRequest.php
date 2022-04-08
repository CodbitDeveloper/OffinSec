<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePatrolAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            "site_id" => "numeric|required",
            "user_id" => "string|required",
            "attendance" => "required|array",
            "attendance.*.present" => "required|boolean",
            "attendance.*.with_permission" => "required|boolean",
            "attendance.*.overtime" => "required|boolean",
            "attendance.*.applicable" => "required|boolean",
            "attendance.*.reliever_id" => "nullable|string|present",
            "attendance.*.guard_id" => "required|string",
        ];
    }
}
