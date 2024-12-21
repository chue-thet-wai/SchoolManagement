<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class SaveDeviceRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'device_id'      => ['string','nullable'],
            'device_token'   => ['required', 'string'],
            'device_os_type' => ['required', 'string','max:255'],
        ];
    }

   
}
