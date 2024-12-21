<?php

namespace App\Http\Requests\API;

use App\Http\Requests\BaseRequest;

class SaveNewsCommentRequest extends BaseRequest
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
            'news_id'                 => ['required', 'string', 'max:255'],
            'comment'                 => ['required','string']
        ];
    }
}
