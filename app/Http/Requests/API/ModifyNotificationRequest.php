<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class ModifyNotificationRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'           => ['required', 'string', 'max:255'],
            'notification_id'   => ['required', 'string','max:255']
        ];
    }

   
}
