<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EditProfileRequest extends BaseRequest
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
        $guardianId = Auth::id();
        return [
            'password' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($guardianId) {
                    // Check if the current password matches the authenticated user's actual password
                    if (!Hash::check($value, Auth::user()->password)) {
                    //if ($value != Auth::user()->password) {
                        $fail('The password is incorrect.');
                    }
                },
            ],
            'name'              => ['required', 'string', 'max:255'], 
            'primary_contact' => [
                'required', 'string', 'max:20',
                Rule::unique('student_guardian', 'phone')
                    ->ignore($this->user()),
            ],
            'secondary_contact' => ['nullable','string', 'max:255'], 
            'email'             => ['nullable','string', 'max:255'],  
            'photo'             => ['nullable','string'],  
            'nrc'               => ['nullable','string', 'max:255'],  
            'address'           => ['nullable','string'],           
        ];
    }
}
