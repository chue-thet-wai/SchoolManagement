<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StudentProfileRequest extends BaseRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'student_id'      => ['required', 'string', 'max:255']
        ];
    }
}
