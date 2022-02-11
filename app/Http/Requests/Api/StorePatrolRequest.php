<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePatrolRequest extends FormRequest
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
            "patrol_officer" => "string|required",
            "notes" => "string|required",
            "scans" => "array|nullable",
            "images" => "nullable|array",
            "user_id" => "nullable|string"
        ];
    }
}
