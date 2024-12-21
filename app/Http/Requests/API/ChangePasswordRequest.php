<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ChangePasswordRequest extends BaseRequest
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
            'current_password' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($guardianId) {
                    // Check if the current password matches the authenticated user's actual password
                    if (!Hash::check($value, Auth::user()->password)) {
                    //if ($value != Auth::user()->password) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'new_password'     => ['required', 'string', 'max:255', 'different:current_password'],
            'confirm_password' => ['required', 'string', 'max:255', 'same:new_password'],
        ];
    }
}
